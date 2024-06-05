<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyAvailabilityRequest;
use App\Http\Requests\StoreAvailabilityRequest;
use App\Http\Requests\UpdateAvailabilityRequest;
use App\Models\Availability;
use App\Models\Service;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class AvailabilityController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('availability_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $availabilities = Availability::with(['service', 'user'])->get();

        return view('admin.availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        abort_if(Gate::denies('availability_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.availabilities.create', compact('services', 'users'));
    }

    public function store(StoreAvailabilityRequest $request)
    {
        $availability = Availability::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $availability->id]);
        }

        return redirect()->route('admin.availabilities.index');
    }

    public function edit(Availability $availability)
    {
        abort_if(Gate::denies('availability_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $availability->load('service', 'user');

        return view('admin.availabilities.edit', compact('availability', 'services', 'users'));
    }

    public function update(UpdateAvailabilityRequest $request, Availability $availability)
    {
        $availability->update($request->all());

        return redirect()->route('admin.availabilities.index');
    }

    public function show(Availability $availability)
    {
        abort_if(Gate::denies('availability_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $availability->load('service', 'user');

        return view('admin.availabilities.show', compact('availability'));
    }

    public function destroy(Availability $availability)
    {
        abort_if(Gate::denies('availability_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $availability->delete();

        return back();
    }

    public function massDestroy(MassDestroyAvailabilityRequest $request)
    {
        $availabilities = Availability::find(request('ids'));

        foreach ($availabilities as $availability) {
            $availability->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('availability_create') && Gate::denies('availability_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Availability();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}

<?php

namespace App\Http\Controllers\Frontend;

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

        return view('frontend.availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        abort_if(Gate::denies('availability_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.availabilities.create', compact('services', 'users'));
    }

    public function store(StoreAvailabilityRequest $request)
    {
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $availability->id]);
        }
        //Check if a record exist for current user
        $available = Availability::where('user_id', $request->user_id)->get();
        if($available->count() > 0){
            //Update the record
            $availability = Availability::where('user_id', $request->user_id)->update(
                [
                    'service_id' => $request->service_id,
                    'zip_code' => $request->zip_code,
                    'message' => $request->message,
                    'date_from' => $request->date_from,
                    'date_to' => $request->date_to,
                ]
            );    
            if($availability ){
                //Send json success message
                return response()->json(['success' => 'Availability status created']);
                } else
                {
                    //Send json error message
                    return response()->json(['error' => 'Error creating availability status']);
                }         
    } else {
        $availability = Availability::create($request->all());

        if($availability ){
        //Send json success message
        return response()->json(['success' => 'Availability status updated']);
        } else
        {
            //Send json error message
            return response()->json(['error' => 'Error updating availability status']);
        }

    }

        
    }

    public function edit(Availability $availability)
    {
        abort_if(Gate::denies('availability_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $availability->load('service', 'user');

        return view('frontend.availabilities.edit', compact('availability', 'services', 'users'));
    }

    public function update(UpdateAvailabilityRequest $request, Availability $availability)
    {
        $availability->update($request->all());

        return redirect()->route('frontend.availabilities.index');
    }

    public function show(Availability $availability)
    {
        abort_if(Gate::denies('availability_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $availability->load('service', 'user');

        return view('frontend.availabilities.show', compact('availability'));
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

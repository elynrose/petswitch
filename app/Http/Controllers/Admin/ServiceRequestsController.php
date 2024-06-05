<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyServiceRequestRequest;
use App\Http\Requests\StoreServiceRequestRequest;
use App\Http\Requests\UpdateServiceRequestRequest;
use App\Models\Pet;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ServiceRequestsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('service_request_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $serviceRequests = ServiceRequest::with(['service', 'pet', 'user'])->get();

        return view('admin.serviceRequests.index', compact('serviceRequests'));
    }

    public function create()
    {
        abort_if(Gate::denies('service_request_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pets = Pet::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.serviceRequests.create', compact('pets', 'services', 'users'));
    }

    public function store(StoreServiceRequestRequest $request)
    {
        $serviceRequest = ServiceRequest::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $serviceRequest->id]);
        }

        return redirect()->route('admin.service-requests.index');
    }

    public function edit(ServiceRequest $serviceRequest)
    {
        abort_if(Gate::denies('service_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pets = Pet::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $serviceRequest->load('service', 'pet', 'user');

        return view('admin.serviceRequests.edit', compact('pets', 'serviceRequest', 'services', 'users'));
    }

    public function update(UpdateServiceRequestRequest $request, ServiceRequest $serviceRequest)
    {
        $serviceRequest->update($request->all());

        return redirect()->route('admin.service-requests.index');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        abort_if(Gate::denies('service_request_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $serviceRequest->load('service', 'pet', 'user');

        return view('admin.serviceRequests.show', compact('serviceRequest'));
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        abort_if(Gate::denies('service_request_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $serviceRequest->delete();

        return back();
    }

    public function massDestroy(MassDestroyServiceRequestRequest $request)
    {
        $serviceRequests = ServiceRequest::find(request('ids'));

        foreach ($serviceRequests as $serviceRequest) {
            $serviceRequest->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('service_request_create') && Gate::denies('service_request_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ServiceRequest();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}

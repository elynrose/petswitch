<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPhotoUpdateRequest;
use App\Http\Requests\StorePhotoUpdateRequest;
use App\Http\Requests\UpdatePhotoUpdateRequest;
use App\Models\Booking;
use App\Models\PhotoUpdate;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PhotoUpdatesController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('photo_update_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photoUpdates = PhotoUpdate::with(['booking', 'user', 'media'])->get();

        return view('admin.photoUpdates.index', compact('photoUpdates'));
    }

    public function create()
    {
        abort_if(Gate::denies('photo_update_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bookings = Booking::pluck('decline', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.photoUpdates.create', compact('bookings', 'users'));
    }

    public function store(StorePhotoUpdateRequest $request)
    {
        $photoUpdate = PhotoUpdate::create($request->all());

        if ($request->input('photo', false)) {
            $photoUpdate->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $photoUpdate->id]);
        }

        return redirect()->route('admin.photo-updates.index');
    }

    public function edit(PhotoUpdate $photoUpdate)
    {
        abort_if(Gate::denies('photo_update_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bookings = Booking::pluck('decline', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $photoUpdate->load('booking', 'user');

        return view('admin.photoUpdates.edit', compact('bookings', 'photoUpdate', 'users'));
    }

    public function update(UpdatePhotoUpdateRequest $request, PhotoUpdate $photoUpdate)
    {
        $photoUpdate->update($request->all());

        if ($request->input('photo', false)) {
            if (! $photoUpdate->photo || $request->input('photo') !== $photoUpdate->photo->file_name) {
                if ($photoUpdate->photo) {
                    $photoUpdate->photo->delete();
                }
                $photoUpdate->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($photoUpdate->photo) {
            $photoUpdate->photo->delete();
        }

        return redirect()->route('admin.photo-updates.index');
    }

    public function show(PhotoUpdate $photoUpdate)
    {
        abort_if(Gate::denies('photo_update_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photoUpdate->load('booking', 'user');

        return view('admin.photoUpdates.show', compact('photoUpdate'));
    }

    public function destroy(PhotoUpdate $photoUpdate)
    {
        abort_if(Gate::denies('photo_update_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photoUpdate->delete();

        return back();
    }

    public function massDestroy(MassDestroyPhotoUpdateRequest $request)
    {
        $photoUpdates = PhotoUpdate::find(request('ids'));

        foreach ($photoUpdates as $photoUpdate) {
            $photoUpdate->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('photo_update_create') && Gate::denies('photo_update_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new PhotoUpdate();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}

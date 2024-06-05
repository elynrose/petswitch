<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPetRequest;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Animal;
use App\Models\Pet;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class PetsController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('pet_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pets = Pet::with(['animal', 'user', 'media'])->where('user_id', Auth::id())->get();

        return view('frontend.pets.index', compact('pets'));
    }

    public function create()
    {
        abort_if(Gate::denies('pet_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $animals = Animal::pluck('animal', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.pets.create', compact('animals', 'users'));
    }

    public function store(StorePetRequest $request)
    {
        //if fire field is not null then the input is spam
        if($request->fire != null){
            return redirect()->route('frontend.pets.index');
        }
        
        $pet = Pet::create($request->all());

        if ($request->input('photos', false)) {
            $pet->addMedia(storage_path('tmp/uploads/' . basename($request->input('photos'))))->toMediaCollection('photos');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $pet->id]);
        }

        return redirect()->route('frontend.pets.index');
    }

    public function edit(Pet $pet)
    {
        abort_if(Gate::denies('pet_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $animals = Animal::pluck('animal', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pet->load('animal', 'user');

        return view('frontend.pets.edit', compact('animals', 'pet', 'users'));
    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $pet->update($request->all());

        if ($request->input('photos', false)) {
            if (! $pet->photos || $request->input('photos') !== $pet->photos->file_name) {
                if ($pet->photos) {
                    $pet->photos->delete();
                }
                $pet->addMedia(storage_path('tmp/uploads/' . basename($request->input('photos'))))->toMediaCollection('photos');
            }
        } elseif ($pet->photos) {
            $pet->photos->delete();
        }

        return redirect()->route('frontend.pets.index');
    }

    public function show(Pet $pet)
    {
        abort_if(Gate::denies('pet_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pet->load('animal', 'user');

        return view('frontend.pets.show', compact('pet'));
    }

    public function destroy(Pet $pet)
    {
        abort_if(Gate::denies('pet_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pet->delete();

        return back();
    }

    public function massDestroy(MassDestroyPetRequest $request)
    {
        $pets = Pet::find(request('ids'));

        foreach ($pets as $pet) {
            $pet->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('pet_create') && Gate::denies('pet_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Pet();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAnimalRequest;
use App\Http\Requests\StoreAnimalRequest;
use App\Http\Requests\UpdateAnimalRequest;
use App\Models\Animal;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnimalController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('animal_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $animals = Animal::all();

        return view('frontend.animals.index', compact('animals'));
    }

    public function create()
    {
        abort_if(Gate::denies('animal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.animals.create');
    }

    public function store(StoreAnimalRequest $request)
    {
        $animal = Animal::create($request->all());

        return redirect()->route('frontend.animals.index');
    }

    public function edit(Animal $animal)
    {
        abort_if(Gate::denies('animal_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.animals.edit', compact('animal'));
    }

    public function update(UpdateAnimalRequest $request, Animal $animal)
    {
        $animal->update($request->all());

        return redirect()->route('frontend.animals.index');
    }

    public function show(Animal $animal)
    {
        abort_if(Gate::denies('animal_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.animals.show', compact('animal'));
    }

    public function destroy(Animal $animal)
    {
        abort_if(Gate::denies('animal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $animal->delete();

        return back();
    }

    public function massDestroy(MassDestroyAnimalRequest $request)
    {
        $animals = Animal::find(request('ids'));

        foreach ($animals as $animal) {
            $animal->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}

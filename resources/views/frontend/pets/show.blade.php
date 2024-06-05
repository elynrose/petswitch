@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.show') }} {{ trans('cruds.pet.title') }}
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.pets.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pet.fields.photos') }}
                                    </th>
                                    <td>
                                        @if($pet->photos)
                                            <a href="{{ $pet->photos->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $pet->photos->getUrl('thumb') }}">
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pet.fields.animal') }}
                                    </th>
                                    <td>
                                        {{ $pet->animal->animal ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pet.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $pet->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pet.fields.breed') }}
                                    </th>
                                    <td>
                                        {{ $pet->breed }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pet.fields.size') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Pet::SIZE_SELECT[$pet->size] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pet.fields.age') }}
                                    </th>
                                    <td>
                                        {{ $pet->age }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pet.fields.gets_along_with') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Pet::GETS_ALONG_WITH_RADIO[$pet->gets_along_with] ?? '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pet.fields.is_immunized') }}
                                    </th>
                                    <td>
                                        <input type="checkbox" disabled="disabled" {{ $pet->is_immunized ? 'checked' : '' }}>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.pet.fields.user') }}
                                    </th>
                                    <td>
                                        {{ $pet->user->name ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('frontend.pets.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
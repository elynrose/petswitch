@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.animal.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.animals.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="animal">{{ trans('cruds.animal.fields.animal') }}</label>
                <input class="form-control {{ $errors->has('animal') ? 'is-invalid' : '' }}" type="text" name="animal" id="animal" value="{{ old('animal', '') }}">
                @if($errors->has('animal'))
                    <div class="invalid-feedback">
                        {{ $errors->first('animal') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.animal.fields.animal_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
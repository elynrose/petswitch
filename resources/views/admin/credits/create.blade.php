@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.credit.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.credits.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="service_request_id">{{ trans('cruds.credit.fields.service_request') }}</label>
                <select class="form-control select2 {{ $errors->has('service_request') ? 'is-invalid' : '' }}" name="service_request_id" id="service_request_id" required>
                    @foreach($service_requests as $id => $entry)
                        <option value="{{ $id }}" {{ old('service_request_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('service_request'))
                    <div class="invalid-feedback">
                        {{ $errors->first('service_request') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.credit.fields.service_request_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="points">{{ trans('cruds.credit.fields.points') }}</label>
                <input class="form-control {{ $errors->has('points') ? 'is-invalid' : '' }}" type="number" name="points" id="points" value="{{ old('points', '') }}" step="1" required>
                @if($errors->has('points'))
                    <div class="invalid-feedback">
                        {{ $errors->first('points') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.credit.fields.points_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.credit.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.credit.fields.user_helper') }}</span>
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
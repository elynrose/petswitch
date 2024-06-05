@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.booking.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.bookings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="service_request_id">{{ trans('cruds.booking.fields.service_request') }}</label>
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
                <span class="help-block">{{ trans('cruds.booking.fields.service_request_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('decline') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="decline" value="0">
                    <input class="form-check-input" type="checkbox" name="decline" id="decline" value="1" {{ old('decline', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="decline">{{ trans('cruds.booking.fields.decline') }}</label>
                </div>
                @if($errors->has('decline'))
                    <div class="invalid-feedback">
                        {{ $errors->first('decline') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.booking.fields.decline_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.booking.fields.user') }}</label>
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
                <span class="help-block">{{ trans('cruds.booking.fields.user_helper') }}</span>
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
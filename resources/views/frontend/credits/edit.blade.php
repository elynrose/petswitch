@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.credit.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.credits.update", [$credit->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="service_request_id">{{ trans('cruds.credit.fields.service_request') }}</label>
                            <select class="form-control select2" name="service_request_id" id="service_request_id" required>
                                @foreach($service_requests as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('service_request_id') ? old('service_request_id') : $credit->service_request->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                            <input class="form-control" type="number" name="points" id="points" value="{{ old('points', $credit->points) }}" step="1" required>
                            @if($errors->has('points'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('points') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.credit.fields.points_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.credit.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $credit->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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

        </div>
    </div>
</div>
@endsection
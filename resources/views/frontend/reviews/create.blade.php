@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.review.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.reviews.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="service_request_id">{{ trans('cruds.review.fields.service_request') }}</label>
                            <select class="form-control select2" name="service_request_id" id="service_request_id" required>
                                @foreach($service_requests as $id => $entry)
                                    <option value="{{ $id }}" {{ old('service_request_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('service_request'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('service_request') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.review.fields.service_request_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="rating">{{ trans('cruds.review.fields.rating') }}</label>
                            <input class="form-control" type="number" name="rating" id="rating" value="{{ old('rating', '') }}" step="1" required>
                            @if($errors->has('rating'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('rating') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.review.fields.rating_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="comment">{{ trans('cruds.review.fields.comment') }}</label>
                            <textarea class="form-control" name="comment" id="comment" required>{{ old('comment') }}</textarea>
                            @if($errors->has('comment'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('comment') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.review.fields.comment_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.review.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.review.fields.user_helper') }}</span>
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
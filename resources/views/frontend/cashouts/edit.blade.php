@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.cashout.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.cashouts.update", [$cashout->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.cashout.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $cashout->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.cashout.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="credits">{{ trans('cruds.cashout.fields.credits') }}</label>
                            <input class="form-control" type="number" name="credits" id="credits" value="{{ old('credits', $cashout->credits) }}" step="1" required>
                            @if($errors->has('credits'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('credits') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.cashout.fields.credits_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="amount">{{ trans('cruds.cashout.fields.amount') }}</label>
                            <input class="form-control" type="number" name="amount" id="amount" value="{{ old('amount', $cashout->amount) }}" step="0.01" required>
                            @if($errors->has('amount'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('amount') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.cashout.fields.amount_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="issued" value="0">
                                <input type="checkbox" name="issued" id="issued" value="1" {{ $cashout->issued || old('issued', 0) === 1 ? 'checked' : '' }}>
                                <label for="issued">{{ trans('cruds.cashout.fields.issued') }}</label>
                            </div>
                            @if($errors->has('issued'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('issued') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.cashout.fields.issued_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="tracking">{{ trans('cruds.cashout.fields.tracking') }}</label>
                            <input class="form-control" type="text" name="tracking" id="tracking" value="{{ old('tracking', $cashout->tracking) }}">
                            @if($errors->has('tracking'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('tracking') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.cashout.fields.tracking_helper') }}</span>
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
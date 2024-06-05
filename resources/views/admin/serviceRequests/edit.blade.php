@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.serviceRequest.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.service-requests.update", [$serviceRequest->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="service_id">{{ trans('cruds.serviceRequest.fields.service') }}</label>
                <select class="form-control select2 {{ $errors->has('service') ? 'is-invalid' : '' }}" name="service_id" id="service_id" required>
                    @foreach($services as $id => $entry)
                        <option value="{{ $id }}" {{ (old('service_id') ? old('service_id') : $serviceRequest->service->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('service'))
                    <div class="invalid-feedback">
                        {{ $errors->first('service') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.serviceRequest.fields.service_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pet_id">{{ trans('cruds.serviceRequest.fields.pet') }}</label>
                <select class="form-control select2 {{ $errors->has('pet') ? 'is-invalid' : '' }}" name="pet_id" id="pet_id" required>
                    @foreach($pets as $id => $entry)
                        <option value="{{ $id }}" {{ (old('pet_id') ? old('pet_id') : $serviceRequest->pet->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('pet'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pet') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.serviceRequest.fields.pet_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="zip_code">{{ trans('cruds.serviceRequest.fields.zip_code') }}</label>
                <input class="form-control {{ $errors->has('zip_code') ? 'is-invalid' : '' }}" type="number" name="zip_code" id="zip_code" value="{{ old('zip_code', $serviceRequest->zip_code) }}" step="1" required>
                @if($errors->has('zip_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zip_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.serviceRequest.fields.zip_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="from">{{ trans('cruds.serviceRequest.fields.from') }}</label>
                <input class="form-control datetime {{ $errors->has('from') ? 'is-invalid' : '' }}" type="text" name="from" id="from" value="{{ old('from', $serviceRequest->from) }}" required>
                @if($errors->has('from'))
                    <div class="invalid-feedback">
                        {{ $errors->first('from') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.serviceRequest.fields.from_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="to">{{ trans('cruds.serviceRequest.fields.to') }}</label>
                <input class="form-control datetime {{ $errors->has('to') ? 'is-invalid' : '' }}" type="text" name="to" id="to" value="{{ old('to', $serviceRequest->to) }}" required>
                @if($errors->has('to'))
                    <div class="invalid-feedback">
                        {{ $errors->first('to') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.serviceRequest.fields.to_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="comments">{{ trans('cruds.serviceRequest.fields.comments') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('comments') ? 'is-invalid' : '' }}" name="comments" id="comments">{!! old('comments', $serviceRequest->comments) !!}</textarea>
                @if($errors->has('comments'))
                    <div class="invalid-feedback">
                        {{ $errors->first('comments') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.serviceRequest.fields.comments_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('pending') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="pending" value="0">
                    <input class="form-check-input" type="checkbox" name="pending" id="pending" value="1" {{ $serviceRequest->pending || old('pending', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="pending">{{ trans('cruds.serviceRequest.fields.pending') }}</label>
                </div>
                @if($errors->has('pending'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pending') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.serviceRequest.fields.pending_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('closed') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="closed" value="0">
                    <input class="form-check-input" type="checkbox" name="closed" id="closed" value="1" {{ $serviceRequest->closed || old('closed', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="closed">{{ trans('cruds.serviceRequest.fields.closed') }}</label>
                </div>
                @if($errors->has('closed'))
                    <div class="invalid-feedback">
                        {{ $errors->first('closed') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.serviceRequest.fields.closed_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.serviceRequest.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $serviceRequest->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.serviceRequest.fields.user_helper') }}</span>
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

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.service-requests.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $serviceRequest->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection
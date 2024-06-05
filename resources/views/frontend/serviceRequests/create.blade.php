@extends('layouts.frontend')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
            @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif


                <div class="card-body">
                    <h1 class="mb-5">{{ trans('cruds.serviceRequest.new') }}</h1>
                    <form method="POST" action="{{ route("frontend.service-requests.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="service_id">{{ trans('cruds.serviceRequest.fields.service') }}</label>
                            <select class="form-control select" name="service_id" id="service_id" required>
                                @foreach($services as $id => $entry)
                                    <option value="{{ $id }}" {{ old('service_id') == $id ? 'selected' : '' }}>{{ ucfirst($entry) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('service'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('service') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.serviceRequest.fields.service_helper') }}</span>
                        </div>
                 
                        <div class="form-group card-body">
    <label class="required" for="pet_id">{{ trans('cruds.serviceRequest.fields.pet') }}</label><br>
    @foreach($pets as $pet)
        <div class="pet-selection" style="display:inline-block; margin: 15px;">
           <p><img src="{{$pet->photos->getUrl('thumb')}}" class="pet-image" data-id="{{ $pet->id }}" id="pet-img-{{ $pet->id }}"></p>
           <p> {{$pet->name ?? ''}} </p>
           <p> <input type="radio" name="pet_id" value="{{ $pet->id }}" class="pet-radio" data-id="{{ $pet->id }}"></p>
        </div>
    @endforeach
    @if($errors->has('pet'))
        <div class="invalid-feedback">
            {{ $errors->first('pet') }}
        </div>
    @endif
    <span class="help-block">{{ trans('cruds.serviceRequest.fields.pet_helper') }}</span>
</div>

                        <div class="form-group">
                            <label class="required" for="zip_code">{{ trans('cruds.serviceRequest.fields.zip_code') }}</label>
                            <input class="form-control" type="number" name="zip_code" id="zip_code" value="{{ old('zip_code', '') }}" step="1" required>
                            @if($errors->has('zip_code'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('zip_code') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.serviceRequest.fields.zip_code_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="from">{{ trans('cruds.serviceRequest.fields.from') }}</label>
                            <input class="form-control datetime" type="text" name="from" id="from" value="{{ old('from') }}" required>
                            @if($errors->has('from'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('from') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.serviceRequest.fields.from_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="to">{{ trans('cruds.serviceRequest.fields.to') }}</label>
                            <input class="form-control datetime" type="text" name="to" id="to" value="{{ old('to') }}" required>
                            @if($errors->has('to'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('to') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.serviceRequest.fields.to_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="comments">{{ trans('cruds.serviceRequest.fields.comments') }}</label>
                            <textarea class="form-control ckeditor" name="comments" id="comments">{!! old('comments') !!}</textarea>
                            @if($errors->has('comments'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('comments') }}
                                </div>
                            @endif
                            <span class="help-block small">{{ trans('cruds.serviceRequest.fields.comments_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success" type="submit">
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                {{ trans('cruds.serviceRequest.fields.submit_request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radios = document.querySelectorAll('.pet-radio');
        radios.forEach(radio => {
            radio.addEventListener('change', function () {
                radios.forEach(r => {
                    const id = r.getAttribute('data-id');
                    const img = document.getElementById(`pet-img-${id}`);
                    if (r.checked) {
                        img.classList.add('selected-pet');
                    } else {
                        img.classList.remove('selected-pet');
                    }
                });
            });
            // Ensure that images are highlighted if radios are pre-checked
            if (radio.checked) {
                const id = radio.getAttribute('data-id');
                const img = document.getElementById(`pet-img-${id}`);
                img.classList.add('selected-pet');
            }
        });
    });
</script>
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
                xhr.open('POST', '{{ route('frontend.service-requests.storeCKEditorImages') }}', true);
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
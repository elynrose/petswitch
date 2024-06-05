@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="profile_photo">{{ trans('cruds.user.fields.profile_photo') }}</label>
                <div class="needsclick dropzone {{ $errors->has('profile_photo') ? 'is-invalid' : '' }}" id="profile_photo-dropzone">
                </div>
                @if($errors->has('profile_photo'))
                    <div class="invalid-feedback">
                        {{ $errors->first('profile_photo') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.profile_photo_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="last_name">{{ trans('cruds.user.fields.last_name') }}</label>
                <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                @if($errors->has('last_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('last_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.last_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bio">{{ trans('cruds.user.fields.bio') }}</label>
                <textarea class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}" name="bio" id="bio">{{ old('bio', $user->bio) }}</textarea>
                @if($errors->has('bio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.bio_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password">
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.country') }}</label>
                <select class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" name="country" id="country">
                    <option value disabled {{ old('country', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\User::COUNTRY_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('country', $user->country) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('country'))
                    <div class="invalid-feedback">
                        {{ $errors->first('country') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.country_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.state') }}</label>
                <select class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" name="state" id="state">
                    <option value disabled {{ old('state', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\User::STATE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('state', $user->state) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('state'))
                    <div class="invalid-feedback">
                        {{ $errors->first('state') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.state_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="city">{{ trans('cruds.user.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', $user->city) }}">
                @if($errors->has('city'))
                    <div class="invalid-feedback">
                        {{ $errors->first('city') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="zip">{{ trans('cruds.user.fields.zip') }}</label>
                <input class="form-control {{ $errors->has('zip') ? 'is-invalid' : '' }}" type="text" name="zip" id="zip" value="{{ old('zip', $user->zip) }}">
                @if($errors->has('zip'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zip') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.zip_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('conscent') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="conscent" value="0">
                    <input class="form-check-input" type="checkbox" name="conscent" id="conscent" value="1" {{ $user->conscent || old('conscent', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="conscent">{{ trans('cruds.user.fields.conscent') }}</label>
                </div>
                @if($errors->has('conscent'))
                    <div class="invalid-feedback">
                        {{ $errors->first('conscent') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.conscent_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('email_notification') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="email_notification" value="0">
                    <input class="form-check-input" type="checkbox" name="email_notification" id="email_notification" value="1" {{ $user->email_notification || old('email_notification', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="email_notification">{{ trans('cruds.user.fields.email_notification') }}</label>
                </div>
                @if($errors->has('email_notification'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email_notification') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_notification_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('sms_notification') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="sms_notification" value="0">
                    <input class="form-check-input" type="checkbox" name="sms_notification" id="sms_notification" value="1" {{ $user->sms_notification || old('sms_notification', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="sms_notification">{{ trans('cruds.user.fields.sms_notification') }}</label>
                </div>
                @if($errors->has('sms_notification'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sms_notification') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.sms_notification_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="banned">{{ trans('cruds.user.fields.banned') }}</label>
                <input class="form-control {{ $errors->has('banned') ? 'is-invalid' : '' }}" type="number" name="banned" id="banned" value="{{ old('banned', $user->banned) }}" step="1">
                @if($errors->has('banned'))
                    <div class="invalid-feedback">
                        {{ $errors->first('banned') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.banned_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <div class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="expiry">{{ trans('cruds.user.fields.expiry') }}</label>
                <input class="form-control date {{ $errors->has('expiry') ? 'is-invalid' : '' }}" type="text" name="expiry" id="expiry" value="{{ old('expiry', $user->expiry) }}">
                @if($errors->has('expiry'))
                    <div class="invalid-feedback">
                        {{ $errors->first('expiry') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.expiry_helper') }}</span>
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
    Dropzone.options.profilePhotoDropzone = {
    url: '{{ route('admin.users.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="profile_photo"]').remove()
      $('form').append('<input type="hidden" name="profile_photo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="profile_photo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->profile_photo)
      var file = {!! json_encode($user->profile_photo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="profile_photo" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}

</script>
@endsection
<div class="row mb-5">
    <div class="col-lg-6">
        <a class="btn btn-success btn-sm" href="{{ route('frontend.service-requests.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.serviceRequest.title_singular') }}
        </a>
        <a class="btn btn-info btn-sm" href="{{ route('frontend.home') }}">
            {{ trans('global.browse') }} {{ trans('cruds.serviceRequest.title') }}
        </a>
    </div>
</div>

@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.serviceRequest.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.service-requests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.serviceRequest.fields.service') }}
                        </th>
                        <td>
                            {{ $serviceRequest->service->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.serviceRequest.fields.pet') }}
                        </th>
                        <td>
                            {{ $serviceRequest->pet->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.serviceRequest.fields.zip_code') }}
                        </th>
                        <td>
                            {{ $serviceRequest->zip_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.serviceRequest.fields.from') }}
                        </th>
                        <td>
                            {{ $serviceRequest->from }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.serviceRequest.fields.to') }}
                        </th>
                        <td>
                            {{ $serviceRequest->to }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.serviceRequest.fields.comments') }}
                        </th>
                        <td>
                            {!! $serviceRequest->comments !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.serviceRequest.fields.pending') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $serviceRequest->pending ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.serviceRequest.fields.closed') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $serviceRequest->closed ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.serviceRequest.fields.user') }}
                        </th>
                        <td>
                            {{ $serviceRequest->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.service-requests.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
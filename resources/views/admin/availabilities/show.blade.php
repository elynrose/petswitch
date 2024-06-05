@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.availability.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.availabilities.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.availability.fields.service') }}
                        </th>
                        <td>
                            {{ $availability->service->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.availability.fields.zip_code') }}
                        </th>
                        <td>
                            {{ $availability->zip_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.availability.fields.message') }}
                        </th>
                        <td>
                            {!! $availability->message !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.availability.fields.user') }}
                        </th>
                        <td>
                            {{ $availability->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.availabilities.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
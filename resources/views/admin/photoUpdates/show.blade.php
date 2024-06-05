@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.photoUpdate.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.photo-updates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.photoUpdate.fields.id') }}
                        </th>
                        <td>
                            {{ $photoUpdate->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photoUpdate.fields.booking') }}
                        </th>
                        <td>
                            {{ $photoUpdate->booking->decline ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photoUpdate.fields.photo') }}
                        </th>
                        <td>
                            @if($photoUpdate->photo)
                                <a href="{{ $photoUpdate->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $photoUpdate->photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photoUpdate.fields.comment') }}
                        </th>
                        <td>
                            {{ $photoUpdate->comment }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photoUpdate.fields.user') }}
                        </th>
                        <td>
                            {{ $photoUpdate->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.photo-updates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
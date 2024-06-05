@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.review.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reviews.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.service_request') }}
                        </th>
                        <td>
                            {{ $review->service_request->zip_code ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.rating') }}
                        </th>
                        <td>
                            {{ $review->rating }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.comment') }}
                        </th>
                        <td>
                            {{ $review->comment }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.review.fields.user') }}
                        </th>
                        <td>
                            {{ $review->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reviews.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
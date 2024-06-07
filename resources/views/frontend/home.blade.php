@extends('layouts.frontend')

@php 
if(Auth::user()->timezone){
    date_default_timezone_set(Auth::user()->timezone);
}
date_default_timezone_set(Auth::user()->timezone);
@endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="mb-5">Incoming Requests</h1>
                    @can('service_request_create')
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <a class="btn btn-success btn-sm" href="{{ route('frontend.service-requests.create') }}">
                                {{ trans('global.add') }} {{ trans('cruds.serviceRequest.title_singular') }}
                            </a>
                        </div>
                        <div class="col-lg-6 mb-5">
                            <!-- Empty column -->
                        </div>
                    </div>
                    @endcan
                    <x-search-form />
                </div>
            </div>
            <div class="card">
                @php
                $today = \Carbon\Carbon::now()->timezone(Auth::user()->timezone);
                @endphp
                @if($serviceRequests->count())
                @foreach($serviceRequests as $key => $serviceRequest)
                @php
                $rating = App\Models\PetReview::where('pet_id', $serviceRequest->pet->id)->avg('rating');
                $rating_count = App\Models\PetReview::where('pet_id', $serviceRequest->pet->id)->count();
                @endphp
                <div class="card shadow-sm mb-5">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-12 pt-4">
                                <div style="position:relative;">
                                    @if($serviceRequest->user->profile_photo)
                                    <img src="{{ $serviceRequest->user->profile_photo->getUrl('thumb') }}" class="user-image shadow" data-id="{{ $serviceRequest->user->id }}" id="user-img-{{ $serviceRequest->user->id }}" style="position: absolute; bottom: 10px; right: 10px; width: 80px; height: 80px; border-radius: 50%;">
                                    @else
                                    <img src="{{ asset('/assets/images/User.png') }}" class="user-image shadow" data-id="{{ $serviceRequest->user->id }}" class="user-image shadow" data-id="{{ $serviceRequest->user->id }}" id="pet-img-{{ $serviceRequest->user->id }}" style="position: absolute; bottom: 10px; right: 10px; width: 80px; height: 80px; border-radius: 50%;">
                                    @endif
                                    <img src="{{ $serviceRequest->pet->photos->getUrl('preview') }}" class="pet-image" data-id="{{ $serviceRequest->pet->id }}" id="pet-img-{{ $serviceRequest->pet->id }}">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="mt-1">{{ $serviceRequest->user->name ?? ''}} {{_('needs')}} {{ $serviceRequest->service->name ?? '' }} {{_('for')}} {{ $serviceRequest->pet->name ?? '' }}</h4>
                                        <p class="small text-muted"> Posted {{ $serviceRequest->created_at->diffForHumans() }}</p>
                                        @if($rating)
                                        <p class="small text-muted">Rating: 
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($rating >= $i)
                                                    <label class="star" style="color: gold;">&#9733;</label>
                                                @else
                                                    <label class="star" style="color:gray;">&#9733;</label>
                                                @endif
                                            @endfor 
                                            ({{$rating_count ? $rating_count.' reviews' : 'No reviews'}})
                                        </p>
                                        @else
                                        <p class="small text-muted">
                                            ({{$rating_count ? $rating_count.' reviews' : 'No reviews'}})
                                        </p>
                                        @endif
                                        @if($serviceRequest->booking)
                                        @php
                                        $today = \Carbon\Carbon::now()->timezone(Auth::user()->timezone);
                                        $fromDateTime = \Carbon\Carbon::parse($serviceRequest->from)->timezone(Auth::user()->timezone);
                                        $toDateTime = \Carbon\Carbon::parse($serviceRequest->to)->timezone(Auth::user()->timezone);
                                        @endphp
                                        @endif
                                        <div class="media-container-row">
                                            <div class="media-content">
                                                @if($serviceRequest->decline == 0 && $serviceRequest->pending == 0 && $fromDateTime > $today)
                                                <p class="badge badge-success">{{_('New')}}</p>
                                                @elseif($fromDateTime < $today && $toDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                                                <p class="badge badge-success">{{_('Booked')}}</p>
                                                @elseif($toDateTime < $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 0)
                                                <p class="badge badge-danger">{{_('Expired')}}</p>
                                                @elseif($fromDateTime <= $today && $toDateTime >= $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1 )
                                                <p class="badge badge-info">{{_('Ongoing')}}</p>
                                                @elseif($fromDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                                                <p class="badge badge-info">{{_('Upcoming')}}</p>
                                                @elseif($serviceRequest->pending==2 && $serviceRequest->decline == 0  && $serviceRequest->to < $today)
                                                <p class="badge badge-warning">{{_('Completed')}}</p>
                                                @endif
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <p><strong>{{_('Size')}}</strong>: {{ $serviceRequest->pet::SIZE_SELECT[$serviceRequest->pet->size] ?? '' }} {{ _('lbs') }}</p>
                                                        <p><strong>{{_('Age')}}</strong>: {{ $serviceRequest->pet->age ?? '' }} {{ _('y/o') }}</p>
                                                        <p><strong>{{_('Gets Along With')}}</strong>: {{ $serviceRequest->pet::GETS_ALONG_WITH_RADIO[$serviceRequest->pet->gets_along_with] ?? '' }}</p>
                                                        <p><strong>{{_('Is Immunized')}}</strong>: <input type="checkbox" disabled="disabled" {{ $serviceRequest->pet->is_immunized ? 'checked' : '' }}></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>{{_('Zip Code')}}</strong>: {{ $serviceRequest->zip_code ?? '' }}</p>
                                                        <p><strong>{{_('Pickup')}}</strong>: {{ \Carbon\Carbon::parse($serviceRequest->from)->format('l, F j, Y, g:i A') ?? '' }}</p>
                                                        <p><strong>{{_('Drop-off')}}</strong>: {{ \Carbon\Carbon::parse($serviceRequest->to)->format('l, F j, Y, g:i A') ?? '' }}</p>
                                                        <p>
                                                            @if($serviceRequest->closed==1)
                                                            <a class="btn btn-success btn-sm" href="{{ route('frontend.service-requests.completed', $serviceRequest->id) }}">{{trans('global.completed')}}</a>
                                                            @elseif($serviceRequest->closed==0)
                                                            <a href="{{ route('frontend.service-requests.show', $serviceRequest->id) }}">{{trans('global.view')}}</a>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-1">
                                                        @if(Auth::id())
                                                        @can('service_request_delete')
                                                        <form action="{{ route('frontend.service-requests.destroy', $serviceRequest->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                            @method('DELETE')
                                                            @csrf
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                        @endcan
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="card-body">
                    <h4>{{_('No Help Needed Right Now. Relax and Chill')}}</h4>
                </div>
                @endif
                @if(!Request::has('zip') && !Request::has('radius'))
                <div class="card-footer">
                    {{ $serviceRequests->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('service_request_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('frontend.service-requests.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    })
                    .done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        });
        let table = $('.datatable-ServiceRequest:not(.ajaxTable)').DataTable({ buttons: dtButtons })
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    })
</script>
@endsection

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
            <div class="col-md-3 col-sm-12 pt-4 text-center">
                <div class="position-relative">
                    @if($serviceRequest->user->profile_photo)
                        <img src="{{ $serviceRequest->user->profile_photo->getUrl('thumb') }}" class="user-image shadow" data-id="{{ $serviceRequest->user->id }}" id="user-img-{{ $serviceRequest->user->id }}" style="position: absolute; bottom: 10px; right: 10px; width: 80px; height: 80px; border-radius: 50%;">
                    @else
                        <img src="{{ asset('/assets/images/User.png') }}" class="user-image shadow" data-id="{{ $serviceRequest->user->id }}" id="user-img-{{ $serviceRequest->user->id }}" style="position: absolute; bottom: 10px; right: 10px; width: 80px; height: 80px; border-radius: 50%;">
                    @endif
                    <img src="{{ $serviceRequest->pet->photos->getUrl('preview') }}" class="pet-image img-fluid rounded" data-id="{{ $serviceRequest->pet->id }}" id="pet-img-{{ $serviceRequest->pet->id }}">
                </div>
            </div>
            <div class="col-md-9">
                <h4 class="mt-1">{{ $serviceRequest->user->name ?? ''}} needs {{ $serviceRequest->service->name ?? '' }} for {{ $serviceRequest->pet->name ?? '' }}</h4>
                <p class="small text-muted">Posted {{ $serviceRequest->created_at->diffForHumans() }}</p>
                @if($rating)
                    <p class="small text-muted">Rating: 
                        @for($i = 1; $i <= 5; $i++)
                            <span class="fa fa-star{{ $rating >= $i ? ' text-warning' : '-o text-muted' }}"></span>
                        @endfor
                        ({{$rating_count ? $rating_count.' reviews' : 'No reviews'}})
                    </p>
                @else
                    <p class="small text-muted">({{$rating_count ? $rating_count.' reviews' : 'No reviews'}})</p>
                @endif
                @if($serviceRequest->booking)
                    @php
                        $today = \Carbon\Carbon::now()->timezone(Auth::user()->timezone);
                        $fromDateTime = \Carbon\Carbon::parse($serviceRequest->from)->timezone(Auth::user()->timezone);
                        $toDateTime = \Carbon\Carbon::parse($serviceRequest->to)->timezone(Auth::user()->timezone);
                    @endphp
                @endif
                <div class="badge-container mb-3">
                    @if($serviceRequest->decline == 0 && $serviceRequest->pending == 0 && $fromDateTime > $today)
                        <span class="badge bg-success text-white">New</span>
                    @elseif($fromDateTime < $today && $toDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                        <span class="badge bg-success text-white">Booked</span>
                    @elseif($toDateTime < $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 0)
                        <span class="badge bg-danger text-white">Expired</span>
                    @elseif($fromDateTime <= $today && $toDateTime >= $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                        <span class="badge bg-info text-white">Ongoing</span>
                    @elseif($fromDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                        <span class="badge bg-info text-white">Upcoming</span>
                    @elseif($serviceRequest->pending == 2 && $serviceRequest->decline == 0 && $serviceRequest->to < $today)
                        <span class="badge bg-warning">Completed</span>
                    @endif
                </div>
                <div class="details">
                    <p><strong>Zip Code:</strong> {{ $serviceRequest->zip_code ?? '' }}</p>
                    <p><strong>Pickup:</strong> {{ \Carbon\Carbon::parse($serviceRequest->from)->format('l, F j, Y, g:i A') ?? '' }}</p>
                    <p><strong>Drop-off:</strong> {{ \Carbon\Carbon::parse($serviceRequest->to)->format('l, F j, Y, g:i A') ?? '' }}</p>
                </div>
                <div class="actions">
                    @if($serviceRequest->closed == 1)
                        <a class="btn btn-success btn-sm" href="{{ route('frontend.service-requests.completed', $serviceRequest->id) }}">{{trans('global.completed')}}</a>
                    @elseif($serviceRequest->closed == 0)
                        <a class="btn btn-primary btn-sm" href="{{ route('frontend.service-requests.show', $serviceRequest->id) }}">{{trans('global.view')}}</a>
                    @endif
                   
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

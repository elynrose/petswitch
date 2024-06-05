@extends('layouts.frontend')
@section('content')

@php 
if(Auth::user()->timezone){
    date_default_timezone_set(Auth::user()->timezone);
}
@endphp

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="mb-5">My Requests</h1>
                    @can('service_request_create')
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-6 mb-5">
                            <a class="btn btn-success btn-sm" href="{{ route('frontend.service-requests.create') }}">
                                {{ trans('global.add') }} {{ trans('cruds.serviceRequest.title_singular') }}
                            </a>
                            <a class="btn btn-info btn-sm" href="{{ route('frontend.home') }}">
                                {{ trans('global.browse') }} {{ trans('cruds.serviceRequest.title') }}
                            </a>
                        </div>
                        <div class="col-lg-6 mb-5">
                        </div>
                    </div>
                    @endcan
                    <form action="{{ route('frontend.service-requests.index') }}" method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="zip" class="form-control" placeholder="{{ __('Enter Zip Code') }}" required>
                            </div>
                            <div class="col-md-4">
                                <select name="radius" class="form-control" required>
                                    <option value="5" {{ request()->input('radius') == 5 ? 'selected' : '' }}>{{ __('5 miles') }}</option>
                                    <option value="10" {{ request()->input('radius') == 10 ? 'selected' : '' }}>{{ __('10 miles') }}</option>
                                    <option value="25" {{ request()->input('radius') == 25 ? 'selected' : '' }}>{{ __('25 miles') }}</option>
                                    <option value="50" {{ request()->input('radius') == 50 ? 'selected' : '' }}>{{ __('50 miles') }}</option>
                                    <option value="100" {{ request()->input('radius') == 100 ? 'selected' : '' }}>{{ __('100 miles') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-sm btn-primary" style="max-height:">{{ __('Search') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                @if($serviceRequests->count())
                
                    @foreach($serviceRequests as $key => $serviceRequest)
                        <div class="card-body shadow-sm mb-5">
                            <div class="row">
                                <div class="col-md-3">
                                    @php
                                    // Get the booking for the service request
                                    $booking = App\Models\Booking::where('service_request_id', $serviceRequest->id)->first();
                                    // Get the user photo
                                    if($booking){
                                        $userPhoto = App\Models\User::where('id', $booking->user_id)->first();
                                    }

                                    @endphp
                                    <div style="position: relative;">
                                        @if($serviceRequest->pet->photos->count() > 0)
                                            <img src="{{ $serviceRequest->pet->photos->getUrl('preview') }}" class="pet-image" data-id="{{ $serviceRequest->pet->id }}" id="pet-img-{{ $serviceRequest->pet->id }}">
                                        @else
                                            <img src="{{ asset('/assets/images/User.png') }}" class="pet-image" data-id="{{ $serviceRequest->pet->id }}" id="pet-img-{{ $serviceRequest->pet->id }}">
                                        @endif
                                        @if($booking && !is_null($userPhoto->profile))
                                            <img src="{{ $userPhoto->profile_photo->getUrl('thumb') }}" class="user-image shadow" data-id="{{ $userPhoto->id }}" id="user-img-{{ $userPhoto->id }}" style="position: absolute; bottom: 10px; right: 10px; width: 80px; height: 80px; border-radius: 50%;">
                                        @else
                                            <img src="{{ asset('/assets/images/User.png') }}" class="pet-image" data-id="{{ $serviceRequest->pet->id }}" id="pet-img-{{ $serviceRequest->pet->id }}" style="position: absolute; bottom: 10px; right: 10px; width: 80px; height: 80px; border-radius: 50%;">
                                        @endif
                                        <!--Get the pets rating-->
                                        @php
                                        $rating = App\Models\Review::where('user_id', $serviceRequest->user_id)->avg('rating');
                                        $rating_count = App\Models\Review::where('user_id', $serviceRequest->user_id)->count();
                                        @endphp
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="pull-right">
                                        @if(Auth::id()==$serviceRequest->user_id)  
                                            @can('service_request_delete')
                                                <form action="{{ route('frontend.service-requests.destroy', $serviceRequest->id) }}" method="POST" onsubmit="return confirm('{{ __('global.areYouSure') }}');" style="display: inline-block;">
                                                    @method('POST')
                                                    @csrf 
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @endcan  
                                        @endif
                                    </div>
                                    <h4>{{ ucfirst($serviceRequest->service->name) ?? '' }} {{_('for')}} {{ $serviceRequest->pet->name ?? '' }}</h4>
                                    <p class="small text-muted"> {{ __('Posted') }} {{ $serviceRequest->created_at->diffForHumans() }}</p>
                                   <!--display pet rating, color the stars based on the rating-->
                                    @if($booking)
                                    
                                    @php
                                        $today = \Carbon\Carbon::now()->timezone(Auth::user()->timezone);
                                        $fromDateTime = \Carbon\Carbon::parse($serviceRequest->from)->timezone(Auth::user()->timezone);
                                        $toDateTime = \Carbon\Carbon::parse($serviceRequest->to)->timezone(Auth::user()->timezone);

                                    @endphp

 @if($serviceRequest->decline == 0 && $serviceRequest->pending == 0 && $fromDateTime > $today)
<p class="badge badge-success">{{ __('New') }}</p>
@elseif($fromDateTime < $today && $toDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
<p class="badge badge-success">{{ __('Booked') }}</p>
@elseif($fromDateTime <= $today && $toDateTime >= $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1 )
<p class="badge badge-info">{{ __('Ongoing') }}</p>
@elseif($fromDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
<p class="badge badge-info">{{ __('Upcoming') }}</p>
@elseif($serviceRequest->pending==2 && $serviceRequest->decline == 0  && $serviceRequest->to < $today && $serviceRequest->closed == 1)
<p class="badge badge-warning">{{ __('Completed') }}</p>
@endif


                                    @endif

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Pickup') }}</strong><br>{{ \Carbon\Carbon::parse($serviceRequest->from)->format('l, F j, Y, g:i A') ?? '' }}</p>
                                           
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>{{ __('Drop-off') }}</strong><br>{{ \Carbon\Carbon::parse($serviceRequest->to)->format('l, F j, Y, g:i A') ?? '' }}</p>  
                                            <p>
                                                @if($serviceRequest->pending==1 && Auth::id()==$serviceRequest->user_id && $serviceRequest->to < $today) 
                                                    <form action="{{ route('frontend.bookings.completed', $serviceRequest->id) }}" method="POST" onsubmit="return confirm(' {{$userPhoto->name ?? 'User'}}  {{ __('global.points_awarded_start') }}{{ __('global.points_awarded_end') }} ');" style="display: inline-block;">
                                                        @method('POST')
                                                        @csrf 
                                                        <input type="hidden" name="service_request_id" value="{{$serviceRequest->id}}">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> &nbsp;{{ __('cruds.serviceRequest.mark_as_completed') }}</button>
                                                    </form>
                                                @elseif($serviceRequest->pending==2 && $serviceRequest->closed==1 && !$booking->review)
                                                <a href="" class="btn btn-sm btn-default" data-toggle="modal" data-target="#reviewModal" data-booking-id="{{ $booking->id }}"><i class="fas fa-star"></i>&nbsp; {{ trans('cruds.serviceRequest.add_review') }}</a>
                                                @endif
                                                @if($serviceRequest->closed==0) 
                                                    <a class="btn btn-primary btn-sm" href="{{ route('frontend.service-requests.show', $serviceRequest->id) }}">{{ __('global.view') }}</a> 
                                                @endif

                                                @if(\Carbon\Carbon::parse($serviceRequest->to)->timezone(Auth::user()->timezone) < $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 0)
                                                <p class="small red">{{ trans('cruds.serviceRequest.expired_request')}} </p> 
                                                @endif

                                            </p>                                              
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


@if(!is_null($booking))
                            <!--create a popup modal for the reviews form and include jquery to send it to reviews.store in reviews controller -->
            <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div id="result"></div>
    <div class="modal-content">
      <form action="{{ route('frontend.reviews.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="reviewModalLabel">Write a review about the time {{$booking->service_request->pet->name}} spent with {{$booking->service_request->user->name}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div class="rating-stars small">
             {{$booking->service_request->user->name}}'s Rating:
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($rating >= $i)
                                                <label class="star" style="color: gold;">&#9733;</label>
                                            @else
                                                <label class="star" style="color:gray;">&#9733;</label>
                                            @endif
                                        @endfor
                                        ({{$rating_count ? $rating_count.' reviews' : 'No reviews'}})
                                        </div>
          <p class="small">We would like to know a little more about your time with {{ $booking->service_request->user->name }}.</p>
          <input type="hidden" name="booking_id" id="booking_id">
            <div class="form-group  row">
            <div class="col-md-12">
              <div class="rating-stars">
                <p class="small">How many stars do you think {{ $booking->service_request->user->name }} deserves.</p>
           <input type="radio"   name="rating" id="rating-1" value="1" >
              <label for="rating-1" class="star px-2 sm-2">One &#9733;</label> 
              <input type="radio"  name="rating" id="rating-2" value="2" >
              <label for="rating-2" class="star px-2 sm-2">Two &#9733;</label>
              <input type="radio"  name="rating" id="rating-3" value="3" >
              <label for="rating-3" class="star px-2 sm-2">Three&#9733;</label>
               <input type="radio"   name="rating" id="rating-4" value="4" >
              <label for="rating-4" class="star px-2 sm-2">Four &#9733;</label>
              <input type="radio"   name="rating" id="rating-5" value="5" >
              <label for="rating-5" class="star px-2 sm-2">Five &#9733;</label>
              </div>
            </div>
            </div>
          <div class="form-group row">
            <div class="col-md-12">
              <p class="small">Write a review</p>
              <textarea name="comment" id="comments" class="form-control ckeditor" required></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="service_request_id" value="{{ $booking->service_request->id }}">
          <input type="hidden" name="user_id" value="{{ Auth::id() }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="booking_id" value="{{ $booking->id }}">
          <button type="submit" class="btn btn-primary btn-sm">{{ _('Submit') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--end of modal-->
@endif

                    @endforeach
                @else
                    <div class="card-body">
                        <h4>{{ __('You have not created any requests!') }}</h4>
                        <p>{{ __('Look for some furry friends to care for in order to earn some points.') }}  <a href="{{ route('frontend.home') }}">
                            {{ __('global.browse') }} {{ __('cruds.serviceRequest.title') }}
                        </a></p>
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
    $(document).ready(function() {
        $('.rating-stars label').on('click', function() {
            var rating = $(this).prev('input').val();
            // Uncheck all stars
            $('.rating-stars input').prop('checked', false);
            // Check the selected star
            $(this).prev('input').prop('checked', true);
            // Reset color of all stars
            $('.rating-stars label').css('color', 'black');
            // Color the stars up to the selected rating
            for(var i = 1; i <= rating; i++) {
                $('#rating-' + i).next('label').css('color', 'gold');
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#bookingForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = form.find('#bookButton');

            // Disable the submit button and show processing status
            submitButton.prop('disabled', true).val('Processing...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if(response.success) {
                        alert('Booking successful');
                        location.reload();
                    } else {
                        alert('Booking failed');
                    }
                },
                error: function() {
                    alert('Booking failed');
                }
            });
        });

        $('#reviewModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var bookingId = button.data('booking-id');
            var modal = $(this);
            modal.find('#booking_id').val(bookingId);
        });

        $('#reviewModal form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = form.find('button[type="submit"]');

            // Disable the submit button and show processing status
            submitButton.prop('disabled', true).text('Processing...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    if(response.status=='success') {
                        /// json success response with success class
                        $('#result').html('<div class="alert alert-success">' + response.message + '</div>');
                        submitButton.prop('disabled', true).text('Done');

                    } else {
                        /* Json error response */
                        $('#result').html('<div class="alert alert-error">' + response.message + '</div>');
                        
                        submitButton.prop('disabled', false).text('Try again...');

                    }
                },
                error: function() {
                    $('#result').html('<div class="alert alert-error">An unknown error occoured. Please try again later.</div>');

                }
            });
        });
    });
</script>

<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('service_request_delete')
            let deleteButtonTrans = '{{ __('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('frontend.service-requests.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });
                    if (ids.length === 0) {
                        alert('{{ __('global.datatables.zero_selected') }}')
                        return
                    }
                    if (confirm('{{ __('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
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

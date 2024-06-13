@extends('layouts.frontend')

@php 
if(Auth::user()->timezone){
    date_default_timezone_set(Auth::user()->timezone);
}
date_default_timezone_set(Auth::user()->timezone);
@endphp

@section('content')
<div class="container py-5">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h1 class="mb-5">{{ trans('cruds.booking.title')}}</h1>

     
        @if($bookings->count() > 0)

          @foreach($bookings as $key => $booking)
            @php
              $from = \Carbon\Carbon::parse($booking->service_request->from)->format('l, F j, Y, g:i A');
              $to = \Carbon\Carbon::parse($booking->service_request->to)->format('l, F j, Y, g:i A');
              $today =  now()->timezone(Auth::user()->timezone);
            @endphp

<div class="card shadow-sm mb-5">
            <div class="card-body" @if($booking->decline==1) style="opacity:0.2!important;" @endif>
              <div class="row mt-3">
                <div class="col-md-3 mb-3">
                  @if($booking->service_request->pet->photos)
                    <img src="{{ $booking->service_request->pet->photos->getUrl('preview') }}" width="100%" class="pet-image">
                  @else
                    <img src="{{ asset('/assets/images/User.png') }}" class="user-image" data-id="{{ $serviceRequest->pet->id }}" id="pet-img-{{ $serviceRequest->pet->id }}" style="filter: grayscale(100%);">
                  @endif
                </div>
                <div class="col-md-9">
                  <h4>{{ ucfirst($booking->service_request->service->name) ?? '' }} {{_('for')}} {{ $booking->service_request->pet->name ?? '' }}</h4><hr>
                  <p class="small text-muted"> Posted {{ $booking->service_request->created_at->diffForHumans() }}</p>
                  @if($booking)              
                      @php
                          $today = \Carbon\Carbon::now()->timezone(Auth::user()->timezone);
                          $fromDateTime = \Carbon\Carbon::parse($booking->service_request->from)->timezone(Auth::user()->timezone);
                          $toDateTime = \Carbon\Carbon::parse($booking->service_request->to)->timezone(Auth::user()->timezone);

                          //get the users rating
                          $rating = App\Models\PetReview::where('pet_id', $booking->pet_id)->avg('rating');
                          //Get the count
                          $rating_count = App\Models\PetReview::where('pet_id', $booking->pet_id)->count();
                      @endphp

@if($booking->service_request->decline == 0 && $booking->service_request->pending == 0 && $fromDateTime > $today)
<p>{{_('New')}}</p>
@elseif($fromDateTime < $today && $toDateTime > $today && $booking->service_request->decline == 0 && $booking->service_request->closed == 0 && $booking->service_request->pending == 1)
<p class="badge badge-success">{{_('Booked')}}</p>
@elseif($toDateTime < $today && $booking->service_request->decline == 0 && $booking->service_request->closed == 0 && $booking->service_request->pending == 0)
<p class="badge badge-danger">{{_('Expired')}}</p>
@elseif($fromDateTime <= $today && $toDateTime >= $today && $booking->service_request->decline == 0 && $booking->service_request->closed == 0 && $booking->service_request->pending == 1 )
<p class="badge badge-info">{{_('Ongoing')}}</p>
@elseif($fromDateTime > $today && $booking->service_request->decline == 0 && $booking->service_request->closed == 0 && $booking->service_request->pending == 1)
<p class="badge badge-info">{{_('Upcoming')}}</p>
@elseif($booking->service_request->pending==2 && $booking->service_request->decline == 0  && $booking->service_request->to < $today)
<p class="badge badge-warning">{{_('Completed')}}</p>
@endif

                    @endif
                  <div class="row">
                    <div class="col-md-6">
                                           <p><strong>{{_('Pickup')}}</strong>: {{ $from ?? '' }}</p>
                              
                    </div>
                    <div class="col-md-6">
                      <p><strong>{{_('Drop-off')}}</strong>: {{ $to ?? '' }}</p>
                      <p>
                        @if($booking->service_request->from > $today && $booking->service_request->to > $today && $booking->service_request->decline == 0 && $booking->service_request->closed ==0  && $booking->service_request->pending == 1)
                          @can('booking_delete')
                            <form id="decline-form-{{ $booking->id }}" action="{{ route('frontend.bookings.decline') }}" method="POST" onsubmit="return confirm('{{ trans('global.decline') }}');" style="display: inline-block;">
                              <input type="hidden" name="_method" value="POST">
                              <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <input type="submit" class="btn btn-sm btn-primary" value="{{ trans('cruds.booking.fields.decline') }}">
                            </form>
                          @endcan
                        @else
                          @if($booking->service_request->from < $today && $booking->service_request->to > $today && $booking->service_request->decline == 0 && $booking->service_request->closed == 0 && $booking->service_request->pending == 1)
                            <p><a href="{{ route('frontend.bookings.show', $booking->id) }}" class="btn btn-sm">View</a> <a href="" class="btn btn-sm">Share</a> <a href="" class="btn btn-sm">Review</a></p>
                        
                            @endif

                          @if($booking->service_request->pending==2 && $booking->service_request->decline == 0  && $booking->service_request->to < $today)
                          <!--add review modal link -->
                            <a href="" class="btn btn-sm btn-default" data-toggle="modal" data-target="#reviewModal" data-booking-id="{{ $booking->id }}"><i class="fas fa-star"></i>&nbsp; Add review </a>
                          <a class="btn btn-sm btn-default"><i class="fas fa-photo px-2"></i> {{_('Photos')}}</a>

                          @endif
                        @endcan
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            </div>


            <!--create a popup modal for the reviews form and include jquery to send it to reviews.store in reviews controller -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('frontend.pet-reviews.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="reviewModalLabel">Write a review about your time with {{$booking->service_request->pet->name}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div class="rating-stars small"> {{$booking->service_request->pet->name}}'s Rating:
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($rating >= $i)
                                                <label class="star" style="color: gold;">&#9733;</label>
                                            @else
                                                <label class="star" style="color:gray;">&#9733;</label>
                                            @endif
                                        @endfor
                                        ({{$rating_count ? $rating_count.' reviews' : 'No reviews'}})
                                        </div>
          <p class="small">We would like to know a little more about your time with {{ $booking->service_request->pet->name }}.</p>
          <input type="hidden" name="booking_id" id="booking_id">
            <div class="form-group  row">
            <div class="col-md-12">
              <div class="rating-stars">
                <p class="small">How many stars do you think {{ $booking->service_request->pet->name }} deserves.</p>
                <input type="radio"   name="rating" id="rating-1 px-2 sm-2" value="1" >
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
          <input type="hidden" name="booking_id" value="{{ $booking->id }}">
          <input type="hidden" name="pet_id" value="{{ $booking->service_request->pet->id }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="booking_id" value="{{ $booking->id }}">
          <button type="submit" class="btn btn-primary btn-sm">{{ _('Submit') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--end of modal-->
          @endforeach
        @else
          <h3>No bookings?</h3>
          <p>Many a pet that would love your attention. Let us help you <a href="{{ route('frontend.home', ['zip'=>Auth::user()->zip, 'radius'=>10]) }}">find one</a>.</p>
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
        $('.rating-stars label').css('color', 'black');
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
            if(response.success) {
              $('#result').html('<div class="alert alert-success">'+response.message+'</div>');
            } else {
              $('#result').html('<div class="alert alert-error">'+response.message+'</div>');
            }
          },
          error: function() {
            $('#result').html('<div class="alert alert-error">An unknown error occoured. Please try again later.</div>');

          }
        });
      });
    });
  <script>

    $(function () {
      let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
      @can('booking_delete')
      let declineButtonTrans = '{{ trans('global.datatables.delete') }}'
      let declineButton = {
        text: declineButtonTrans,
        url: "{{ route('frontend.bookings.decline') }}",
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
              data: { ids: ids, _method: 'POST' }
            })
            .done(function () { location.reload() })
          }
        }
      }
      dtButtons.push(declineButton)
      @endcan

      $.extend(true, $.fn.dataTable.defaults, {
        orderCellsTop: true,
        order: [[ 1, 'desc' ]],
        pageLength: 100,
      });

      let table = $('.datatable-Booking:not(.ajaxTable)').DataTable({ buttons: dtButtons })

      $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
      });

      var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
    })
  </script>
@endsection

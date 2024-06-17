@extends('layouts.frontend')

@php 
if(Auth::user()->timezone){
    date_default_timezone_set(Auth::user()->timezone);
}
@endphp

@section('content')
<section class="py-5 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
              
                    @php
                        $today = \Carbon\Carbon::now()->timezone(Auth::user()->timezone);
                        $fromDateTime = \Carbon\Carbon::parse($serviceRequest->from)->timezone(Auth::user()->timezone);
                        $toDateTime = \Carbon\Carbon::parse($serviceRequest->to)->timezone(Auth::user()->timezone);
                        $rating = App\Models\PetReview::where('pet_id', $serviceRequest->pet->id)->avg('rating');
                        $rating_count = App\Models\PetReview::where('pet_id', $serviceRequest->pet->id)->count();
                    @endphp
            
                <div class="media-container-row">
                    <div class="media-content">
                        @if($serviceRequest->decline == 0 && $serviceRequest->pending == 0 && $fromDateTime > $today)
                            <span class="badge badge-success text-white">New</span>
                        @elseif($fromDateTime < $today && $toDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                            <span class="badge badge-success text-white">Booked</span>
                        @elseif($toDateTime < $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 0)
                            <span class="badge badge-danger text-white">Expired</span>
                        @elseif($fromDateTime <= $today && $toDateTime >= $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                            <span class="badge badge-info text-white">Ongoing</span>
                        @elseif($fromDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                            <span class="badge badge-info text-white">Upcoming</span>
                        @elseif($serviceRequest->pending == 2 && $serviceRequest->decline == 0 && $serviceRequest->to < $today)
                            <span class="badge badge-warning">Completed</span>
                        @endif

                        <h1 class="text-white display-2 mt-3">
                            <strong>{{ $serviceRequest->pet->name ?? '' }}</strong>
                        </h1>

                        @if($rating_count > 0)
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="text-white">{{ round($rating, 2) }} ({{ $rating_count }} Reviews)</span>
                        @else
                            <i class="far fa-star text-warning"></i> <span class="text-white">No Rating</span>
                        @endif

                        <p class="text-white mt-3">
                            {{ $serviceRequest->user->name }} says:
                            <em class="text-white"> {!! $serviceRequest->comments !!}</em>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" style="background:url({{ $serviceRequest->pet->photos->getUrl('preview') }}); background-size:cover; border-radius:1rem; background-repeat:no-repeat;">
            </div>
        </div>
    </div>
</section>

<section class="mbr-section form6 agencym4_form6" id="form6-3m">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mt-5 mb-5">
                <div class="text-block">
                    <div class="mb-4">
                        <p class="mb-4"><strong>Service</strong>
						: {{ ucfirst($serviceRequest->service->name) ?? '' }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="mb-4"><strong>Credits</strong>
                        : {{ \Carbon\Carbon::parse($serviceRequest->from)->diffInHours(\Carbon\Carbon::parse($serviceRequest->to)) ?? '' }} credits</p>
                    </div>
                    <div class="mb-4">
                        <p class="mb-4"><strong>Pickup Date</strong>
                        : {{ \Carbon\Carbon::parse($serviceRequest->from)->format('l, F j, Y, g:i A') ?? '' }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="mb-4"><strong>Return Date</strong>
                        : {{ \Carbon\Carbon::parse($serviceRequest->to)->format('l, F j, Y, g:i A') ?? '' }}</p>
                    </div>
                    <p><strong>Size</strong>: {{ $serviceRequest->pet::SIZE_SELECT[$serviceRequest->pet->size] ?? '' }} lbs</p>
                    <p><strong>Age</strong>: {{ $serviceRequest->pet->age ?? '' }} y/o</p>
                    <p><strong>Gets Along With</strong>: {{ $serviceRequest->pet::GETS_ALONG_WITH_RADIO[$serviceRequest->pet->gets_along_with] ?? '' }}</p>
                    <p><strong>Is Immunized</strong>: <input type="checkbox" disabled {{ $serviceRequest->pet->is_immunized ? 'checked' : '' }}></p>
                </div>
            </div>
            <div class="col-md-6 block-content mt-5">

            
          
                <div class="google-map"><iframe width="100%" height="300" frameborder="0" src="https://www.google.com/maps?q={{ $serviceRequest->zip_code ?? ''}}&output=embed"></iframe></div>
           
                <div>
                    @if($serviceRequest->closed == 0 && Auth::id() !== $serviceRequest->user_id && $serviceRequest->pending == 0 && $serviceRequest->decline == 0)
                        <form id="bookingForm" action="{{ route('frontend.bookings.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="panel pb-4">
                                <p class="small">
								<i class="fas fa-warning gold"></i> Please be certain that you have the time blocked and that the dates work for you.
                                    You may lose points if you decline this service.
                                    As much as we love to help, we also hate to disappoint.
                                </p>
                            </div>
                            <input type="hidden" name="credits" required value="{{ \Carbon\Carbon::parse($serviceRequest->from)->diffInHours(\Carbon\Carbon::parse($serviceRequest->to)) ?? '' }}">
                            <input type="hidden" name="service_request_id" required value="{{ $serviceRequest->id }}">
                            <input type="hidden" name="from" required value="{{ $serviceRequest->from }}">
                            <input type="hidden" name="to" required value="{{ $serviceRequest->to }}">
                            <input type="hidden" name="user_id" required value="{{ Auth::id() }}">
                            <button type="submit" class="btn btn-primary btn-bgr display-4" id="bookButton">Book {{ $serviceRequest->pet->name ?? '' }}</button>
                        </form>
                    @else
                        @if(Auth::id() == $serviceRequest->user_id && $serviceRequest->pending == 0)
                            <a class="btn btn-success" href="{{ route('frontend.service-requests.edit', $serviceRequest->id) }}">
                                <i class="fas fa-edit"></i> {{ trans('global.edit') }} {{ trans('cruds.serviceRequest.title_singular') }}
                            </a>
                        @else
                            <a class="btn btn-success" href="{{ route('frontend.service-requests.index') }}">
                                <i class="fas fa-arrow-left"></i> {{ trans('global.in_progress') }}
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('#bookingForm').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var submitButton = form.find('#bookButton');

            submitButton.prop('disabled', true).text('Processing...');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(response) {
                    alert('Booking successful!');
                    window.location.href = "{{ route('frontend.bookings.index') }}";
                },
                error: function(xhr, status, error) {
                    alert('An error occurred. Please try again.');
                    submitButton.prop('disabled', false).text('Book {{ $serviceRequest->pet->name ?? '' }}');
                }
            });
        });
    });
</script>
@endsection

@extends('layouts.frontend')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">


         <div class="card">
               <h3 class="mb-3"><strong>Booking for  {{ $booking->service_request->pet->name }}</strong></h3>
               <p>   {{ $booking->user->name ?? '' }} requested {{ ucfirst($booking->service_request->service->name) ?? '' }} for {{ $booking->service_request->pet->name }}</p>
               <div class="card-body">
                <div class="row">
                <div class="col-md-3">
                    <img src="{{ $booking->service_request->pet->photos->getUrl('preview') }}"  style="width:100%;">
                </div>
                <div class="col-md-5">
                <div class="text-block">
                    <div class="mb-4">
                     <h4>Details</h4>
                    </div>
                    <div class="mb-4">
                        <p class="mb-4"><strong>Credits</strong>
                        : {{ \Carbon\Carbon::parse($booking->service_request->from)->diffInHours(\Carbon\Carbon::parse($booking->service_request->to)) ?? '' }} credits</p>
                    </div>
                    <div class="mb-4">
                        <p class="mb-4"><strong>Pickup Date</strong>
                        : {{ \Carbon\Carbon::parse($booking->service_request->from)->format('l, F j, Y, g:i A') ?? '' }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="mb-4"><strong>Return Date</strong>
                        : {{ \Carbon\Carbon::parse($booking->service_request->to)->format('l, F j, Y, g:i A') ?? '' }}</p>
                    </div>
                    <p><strong>Size</strong>: {{ $booking->service_request->pet::SIZE_SELECT[$booking->service_request->pet->size] ?? '' }} lbs</p>
                    <p><strong>Age</strong>: {{$booking->service_request->pet->age ?? '' }} y/o</p>
                    <p><strong>Gets Along With</strong>: {{ $booking->service_request->pet::GETS_ALONG_WITH_RADIO[$booking->service_request->pet->gets_along_with] ?? '' }}</p>
                    <p><strong>Is Immunized</strong>: <input type="checkbox" disabled {{ $booking->service_request->pet->is_immunized ? 'checked' : '' }}></p>
                </div>
               
</div>
<div class="col-md-4">
                    <h4>About {{ $booking->service_request->pet->name }}</h4>
                   <p>{!! $booking->service_request->comments ?? 'No Comments' !!}</p>
                </div>
            </div>
                </div>


             

        </div>
    </div>
</div>
@endsection
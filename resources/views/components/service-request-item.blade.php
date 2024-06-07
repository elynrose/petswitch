@props(['serviceRequest', 'booking', 'userPhoto', 'rating', 'ratingCount', 'today', 'fromDateTime', 'toDateTime'])

<div class="card-body shadow-sm mb-5">
    <div class="row">
        <div class="col-md-3">
            <div style="position: relative;">
                <img src="{{ $serviceRequest->pet->photos->count() > 0 ? $serviceRequest->pet->photos->getUrl('preview') : asset('/assets/images/User.png') }}" class="pet-image" data-id="{{ $serviceRequest->pet->id }}" id="pet-img-{{ $serviceRequest->pet->id }}">
                @if($userPhoto && !is_null($userPhoto->profile))
                    <img src="{{ $userPhoto->profile_photo->getUrl('thumb') }}" class="user-image shadow" data-id="{{ $userPhoto->id }}" id="user-img-{{ $userPhoto->id }}" style="position: absolute; bottom: 10px; right: 10px; width: 80px; height: 80px; border-radius: 50%;">
                @else
                    <img src="{{ asset('/assets/images/User.png') }}" class="pet-image" data-id="{{ $serviceRequest->pet->id }}" id="pet-img-{{ $serviceRequest->pet->id }}" style="position: absolute; bottom: 10px; right: 10px; width: 80px; height: 80px; border-radius: 50%;">
                @endif
            </div>
        </div>
        <div class="col-md-9">
            <div class="pull-right">
                @if(Auth::id() == $serviceRequest->user_id)
                    @can('service_request_delete')
                        <form action="{{ route('frontend.service-requests.destroy', $serviceRequest->id) }}" method="POST" onsubmit="return confirm('{{ __('global.areYouSure') }}');" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    @endcan
                @endif
            </div>
            <h4>{{ ucfirst($serviceRequest->service->name) ?? '' }} {{__('for')}} {{ $serviceRequest->pet->name ?? '' }}</h4>
            <p class="small text-muted">{{ __('Posted') }} {{ $serviceRequest->created_at->diffForHumans() }}
                @if($booking)
                    and booked by <a href="{{ route('frontend.users.show', $booking->user->id) }}" target="_blank">{{ $booking->user->name ?? '' }}</a>
                @endif
            </p>
            @if($booking)
                @if($serviceRequest->decline == 0 && $serviceRequest->pending == 0 && $fromDateTime > $today)
                    <p class="badge badge-success">{{ __('New') }}</p>
                @elseif($fromDateTime < $today && $toDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                    <p class="badge badge-success">{{ __('Booked') }}</p>
                @elseif($fromDateTime <= $today && $toDateTime >= $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                    <p class="badge badge-info">{{ __('Ongoing') }}</p>
                @elseif($fromDateTime > $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 1)
                    <p class="badge badge-info">{{ __('Upcoming') }}</p>
                @elseif($serviceRequest->pending == 2 && $serviceRequest->decline == 0 && $serviceRequest->to < $today && $serviceRequest->closed == 1)
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
                        @if($serviceRequest->pending == 1 && Auth::id() == $serviceRequest->user_id && $serviceRequest->to < $today)
                            <form action="{{ route('frontend.bookings.completed', $serviceRequest->id) }}" method="POST" onsubmit="return confirm('{{ __('global.points_awarded_start') }} {{ __('global.points_awarded_end') }}');" style="display: inline-block;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> &nbsp;{{ __('cruds.serviceRequest.mark_as_completed') }}</button>
                            </form>
                        @elseif($serviceRequest->pending == 2 && $serviceRequest->closed == 1 && !$booking->review)
                            <a href="#" class="btn btn-sm btn-default" data-toggle="modal" data-target="#reviewModal" data-booking-id="{{ $booking->id }}"><i class="fas fa-star"></i>&nbsp; {{ trans('cruds.serviceRequest.add_review') }}</a>
                        @endif
                        @if($serviceRequest->closed == 0)
                            <a class="btn btn-primary btn-sm" href="{{ route('frontend.service-requests.show', $serviceRequest->id) }}">{{ __('global.view') }}</a>
                        @endif
                        @if(\Carbon\Carbon::parse($serviceRequest->to)->timezone(Auth::user()->timezone) < $today && $serviceRequest->decline == 0 && $serviceRequest->closed == 0 && $serviceRequest->pending == 0)
                            <p class="small text-danger">{{ trans('cruds.serviceRequest.expired_request') }}</p>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

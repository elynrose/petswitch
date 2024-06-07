@props(['booking', 'rating', 'ratingCount'])

<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div id="result"></div>
        <div class="modal-content">
            <form action="{{ route('frontend.reviews.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Write a review about the time {{ $booking->service_request->pet->name }} spent with {{ $booking->service_request->user->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="rating-stars small">
                        {{ $booking->service_request->user->name }}'s Rating:
                        @for($i = 1; $i <= 5; $i++)
                            <label class="star" style="color: {{ $rating >= $i ? 'gold' : 'gray' }};">&#9733;</label>
                        @endfor
                        ({{ $ratingCount ? $ratingCount . ' reviews' : 'No reviews' }})
                    </div>
                    <p class="small">We would like to know a little more about your time with {{ $booking->service_request->user->name }}.</p>
                    <input type="hidden" name="booking_id" id="booking_id">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="rating-stars">
                                <p class="small">How many stars do you think {{ $booking->service_request->user->name }} deserves.</p>
                                @foreach([1, 2, 3, 4, 5] as $star)
                                    <input type="radio" name="rating" id="rating-{{ $star }}" value="{{ $star }}">
                                    <label for="rating-{{ $star }}" class="star px-2 sm-2">{{ $star }} &#9733;</label>
                                @endforeach
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
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('Submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

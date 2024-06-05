@extends('layouts.frontend')

@section('content')

<section class="header2 agencym4_header2 cid-ucXxDHKGIF beautiful-background" id="header2-3y">
    <div class="container">
        <div class="media-container-row">
            <div class="media-content">
                <h1 class="mbr-section-title mbr-white pb-3 mbr-fonts-style display-2"><br>About <strong>{{ $user->name ?? 'User'}}</strong></h1>
                <div class="mbr-section-text mbr-white pb-3 ">
                    <p class="mbr-text mbr-fonts-style display-5">
                        @if($user->id == Auth::user()->id && !$user->bio)  
                            {!! $user->bio ?? 'Update your bio on the profile page' !!}
                            <a href="{{ route('frontend.profile.index') }}"><i class="fas fa-edit"></i></a>
                        @else
                            {!! $user->bio ?? 'No bio available' !!}
                        @endif
                    </p>
                    <p style="padding:10px; background-color:; color:black;">
                        <i class="fas fa-home"></i> {{ $user->city ?? '' }}@if($user->state) {{_(',')}} @endif{{ $user->state ?? '' }}<br>
                        <i style="color:gray;" class="fas fa-money"></i> Total Credits: {{ $total_credits->points ?? 0 }} <br> 
                        <i style="color:pink;" class="fas fa-calendar"></i> Member Since: {{ $user->created_at->diffForHumans() }}<br>
                        <i class="fas fa-clock"></i> Hours spent with pets: {{ $total_credits->points ?? 0 }}<br>
                        <i style="color:green;" class="fas fa-paw"></i> Pet Family: {{ $total_pets ?? 'None' }}<br>
                        <i style="color:gold;" class="fas fa-star"></i> Rating: {{ round($average_rating, 2) ?? 0}}<br>
                    </p>
                    {{Auth::user()->id}}
                    @if($user->id == Auth::user()->id)
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#appointmentModal">
                            Set your next availability
                        </button>
                    @endif
                </div>
            </div>
            <div class="mbr-figure" style="width: 100%;">
            @if(!is_null($user->profile_photo))
                <img src="{{ $user->profile_photo->getUrl() }}" alt="{{ $user->name }}">
                @else
                <img src="{{ asset('/assets/images/User.png') }}" alt="{{ $user->name }}">
            @endif
            </div>
        </div>
    </div>
</section>

@if($next_available_date)
<section class="countdown2 agencym4_countdown2 cid-ucXxDIxZST" id="countdown2-3z">
    <div class="container-fluid pt-5 pb-5 full-count-container counter">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h2 class="mbr-section-title mbr-fonts-style mbr-white display-2"><strong>Available for  </strong> <br>{{ strtolower($next_available_date->service->name) }} in</h2>
                </div>
                <div class="countdown-cont align-center col-lg-6">
                    <div class="daysCountdown col-xs-3 col-sm-3 col-md-3" title="Days"></div>
                    <div class="hoursCountdown col-xs-3 col-sm-3 col-md-3" title="Hours"></div>
                    <div class="minutesCountdown col-xs-3 col-sm-3 col-md-3" title="Minutes"></div>
                    <div class="secondsCountdown col-xs-3 col-sm-3 col-md-3" title="Seconds"></div>
                    <div class="countdown" data-due-date="{{ \Carbon\Carbon::parse($next_available_date->date_from)->timezone(Auth::user()->timezone) }}"></div>
                   
                </div>
            </div>
        </div>
    </div>
</section>
@elseif(!$next_available_date)
<section class="countdown2  agencym4_countdown2 cid-ucXxDIxZST" id="countdown2-3z">
    <div class="container-fluid pt-5 pb-5 full-count-container counter">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <h2 class="mbr-section-title mbr-fonts-style mbr-white display-2"><strong>Status set to </strong> unavailable</h2>
                </div>
                <div class="countdown-cont align-center col-lg-6">
                    <div class="daysCountdown col-xs-3 col-sm-3 col-md-3" title="Days"></div>
                    <div class="hoursCountdown col-xs-3 col-sm-3 col-md-3" title="Hours"></div>
                    <div class="minutesCountdown col-xs-3 col-sm-3 col-md-3" title="Minutes"></div>
                    <div class="secondsCountdown col-xs-3 col-sm-3 col-md-3" title="Seconds"></div>
                    <div class="countdown" data-due-date=""></div>
                    @if($user->id == Auth::user()->id)
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#appointmentModal">
                            Set your next availability
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Tell us about your availability</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="status"></div>
                <form method="POST" action="{{ route("frontend.availabilities.store") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="required" for="service_id">{{ trans('cruds.availability.fields.service') }}</label>
                        <select class="form-control select" name="service_id" id="service_id" required>
                            @foreach($services as $id => $entry)
                                <option value="{{ $id }}" {{ old('service_id') == $id ? 'selected' : '' }}>{{ ucfirst($entry) }}</option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <span class="help-block">{{ trans('cruds.availability.fields.service_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="zip_code">{{ trans('cruds.availability.fields.zip_code') }}</label>
                        <input class="form-control" type="text" name="zip_code" id="zip_code" value="{{ Auth::user()->zip ?? old('zip_code') }}" required>
                        @error('zip_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <span class="help-block">{{ trans('cruds.availability.fields.zip_code_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="date_from">{{ trans('cruds.availability.fields.date_from') }}</label>
                        <input class="form-control datetime" type="text" name="date_from" id="date_from" value="{{ old('date_from') }}" required>
                        @error('date_from')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <span class="help-block">{{ trans('cruds.availability.fields.date_from_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="required" for="date_to">{{ trans('cruds.availability.fields.date_to') }}</label>
                        <input class="form-control datetime" type="text" name="date_to" id="date_to" value="{{ old('date_to') }}" required>
                        @error('date_to')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <span class="help-block">{{ trans('cruds.availability.fields.date_to_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label for="message">{{ trans('cruds.availability.fields.message') }}</label>
                        <textarea class="form-control ckeditor" name="message" id="message">{!! old('message') !!}</textarea>
                        @error('message')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <span class="help-block">{{ trans('cruds.availability.fields.message_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <button class="btn btn-info" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>

<!--<section class="cid-ucXyeK3nEH" id="social-buttons1-43">
    <div class="container">
        <div class="media-container-row">
            <a href="https://twitter.com/mobirise" target="_blank">
                <span class="px-2 mbr-iconfont mbr-iconfont-social socicon-twitter socicon" style="font-size: 64px;"></span>
                <p class="mbr-fonts-style display-7">Follow on Twitter</p>
            </a>
            <a href="https://www.facebook.com/pages/Mobirise/1616226671953247" target="_blank">
                <span class="px-2 mbr-iconfont mbr-iconfont-social socicon-facebook socicon" style="font-size: 64px;"></span>
                <p class="mbr-fonts-style display-7">Follow on Facebook</p>
            </a>
            <a href="https://nl.pinterest.com/mobirise/" target="_blank">
                <span class="px-2 mbr-iconfont mbr-iconfont-social fa-instagram fa" style="font-size: 64px;"></span>
                <p class="mbr-fonts-style display-7">Follow on Pinterest</p>
            </a>
        </div>
    </div>
</section>-->

<section class="mbr-section form1 agencym4_form1 cid-ucXybVJ7dq" id="form1-42">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="google-map"><iframe src="https://www.google.com/maps?q={{ $user->state}},{{ $user->city}}&output=embed"></iframe></div>
            </div>
        </div>
    </div>
</section>


@endsection

@section('scripts')
@parent
<script src="/assets/countdown/jquery.countdown.min.js"></script>
<script>
$(document).ready(function(){
    $('#appointmentModal form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response){
                $('#status').addClass('alert alert-success').html(response.success);
                location.reload();
            },
            error: function(response){
                $('#status').addClass('alert alert-success').html(response.error);
            }
        });
    });
});
</script>
@endsection

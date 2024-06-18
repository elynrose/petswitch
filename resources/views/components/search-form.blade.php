<form action="{{ url()->current() }}" method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-4 mb-3">
            <input type="text" name="zip" class="form-control" placeholder="{{ __('Enter Zip Code') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <select name="radius" class="form-control" required>
                @foreach([5, 10, 25, 50, 100] as $miles)
                    <option value="{{ $miles }}" {{ request()->input('radius') == $miles ? 'selected' : '' }}>{{ __("$miles miles") }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-sm btn-primary">{{ __('Search') }}</button>
        </div>
    </div>
</form>


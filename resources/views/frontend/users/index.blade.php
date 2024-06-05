@extends('layouts.frontend')
@section('content')

<section class="features15 agencym4_features15 cid-ucRMkyKyoy" id="features15-3a">
    
	<div class="container">
    <h1 class="mb-5">Members in your area</h1>
    <form action="{{ route('frontend.users.index') }}" method="get">
        <div class="form-row">
            <div class="form-group
            col-md-3">
                <input type="text" class="form-control" id="zip" name="zip" value="{{ request()->input('zip') }}">
            </div>
            <div class="form-group col-md-6">
                <select id="radius" class="form-control" name="radius">
                    <option value="5" {{ request()->input('radius') == 5 ? 'selected' : '' }}>5 miles</option>
                    <option value="10" {{ request()->input('radius') == 10 ? 'selected' : '' }}>10 miles</option>
                    <option value="25" {{ request()->input('radius') == 25 ? 'selected' : '' }}>25 miles</option>
                    <option value="50" {{ request()->input('radius') == 50 ? 'selected' : '' }}>50 miles</option>
                    <option value="100" {{ request()->input('radius') == 100 ? 'selected' : '' }}>100 miles</option>
                </select>
            </div>
            <div class="form-group col-md-3">
            <button type="submit" class="btn btn-primary">Search</button>

</div>
        </div>
    </form>

		<div class="media-container-row">
        @foreach($users as $key => $user)
			<div class="card col-12 col-md-3 col-lg-3">
				<div class="card-img">
          <a href="{{ route('frontend.users.show', $user->id ) }}">
                    @if($user->profile_photo)
                                                <img src="{{ $user->profile_photo->getUrl('preview') }}">
                                        @else 
                                        <img src="{{ asset('/assets/images/User.png') }}">
                                      @endif</a>
				</div>
				<div class="card-box">
					<h4 class="card-title mbr-fonts-style display-5">{{ $user->name ?? 'User' }}</h4>
					<p class="mbr-text mbr-fonts-style display-4"><strong>{{ $user->city ?? '' }}@if($user->state) {{_(',')}} @endif{{ $user->state ?? '' }}   </strong></p>
          <p class="display-9 small">Total Credits: {{ App\models\Credit::where('user_id', $user->id)->sum('points') ?? 'New' }} <br> 
          Member Since: {{ $user->created_at->diffForHumans() }}
          
        </p>


					<div class="mbr-section-btn"><a class="btn-underline display-4" href="{{ route('frontend.users.show', $user->id ) }}">Profile</a></div>
				</div>
			</div>
            @endforeach       
		
			
		</div>

        <div class="pagination">
            @if(!Request::get('zip') && !Request::get('radius'))
                {{ $users->links() }}
            @endif
            </div>

	</div>
</section>





@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('user_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.users.massDestroy') }}",
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
          data: { ids: ids, _method: 'DELETE' }})
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
  let table = $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
@extends('layouts.frontend')

@section('content')
<section class="features15 agencym4_features15 cid-ucRMkyKyoy" id="features15-3a">
  <div class="container">
    <h1 class="mb-5">{{ trans('cruds.pet.my_pets') }}</h1>
    <div class="row">
      <div class="col-lg-12">
        @can('pet_create')
        <a class="btn btn-success btn-sm mb-5" href="{{ route('frontend.pets.create') }}">
          {{ trans('global.add') }} {{ trans('cruds.pet.title_singular') }}
        </a>
        @endcan
      </div>
    </div>
    <div class="row">
      @foreach($pets as $key => $pet)
      <div class="col-12 col-md-4 col-lg-3">
        <div class="card shadow-sm">
          <div class="card-img">
            @if($pet->photos)
            <img src="{{ $pet->photos->getUrl('preview') }}">
            @endif
          </div>
          <div class="card-box">
            <h4 class="card-title mbr-fonts-style display-5">{{ $pet->name ?? '' }}</h4>
            <p class="small">
            <!--Count completed service requests-->
            {{ \App\Models\ServiceRequest::where('closed', 1)->where('pet_id', $pet->id)->count() }} {{ _('completed request(s)') }}
            </p>
          </div>
          <div class="justify-content-center">
            @can('pet_edit')
            <a class="btn btn-xs btn-info" href="{{ route('frontend.pets.edit', $pet->id) }}">
            <i class="fas fa-edit"></i>
            </a>
            @endcan
            @can('pet_delete')
            <form action="{{ route('frontend.pets.destroy', $pet->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
              @method('DELETE')
              @csrf
              <button type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}"><i class="fas fa-trash"></i></button>
            </form>
            @endcan
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection

@section('scripts')
@parent
<script>
  $(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
    @can('pet_delete')
    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
    let deleteButton = {
      text: deleteButtonTrans,
      url: "{{ route('frontend.pets.massDestroy') }}",
      className: 'btn-danger',
      action: function (e, dt, node, config) {
        var ids = dt.rows({ selected: true }).nodes().toArray().map(function (entry) {
          return $(entry).data('entry-id');
        });

        if (ids.length === 0) {
          alert('{{ trans('global.datatables.zero_selected') }}');
          return;
        }

        if (confirm('{{ trans('global.areYouSure') }}')) {
          $.ajax({
            headers: { 'x-csrf-token': _token },
            method: 'POST',
            url: config.url,
            data: { ids: ids, _method: 'DELETE' }
          })
          .done(function () { location.reload(); });
        }
      }
    };
    dtButtons.push(deleteButton);
    @endcan

    $.extend(true, $.fn.dataTable.defaults, {
      orderCellsTop: true,
      order: [[1, 'desc']],
      pageLength: 100,
    });

    let table = $('.datatable-Pet:not(.ajaxTable)').DataTable({ buttons: dtButtons });

    $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
      $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
  });
</script>
@endsection

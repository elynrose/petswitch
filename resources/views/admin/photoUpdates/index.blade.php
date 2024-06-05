@extends('layouts.admin')
@section('content')
@can('photo_update_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.photo-updates.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.photoUpdate.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.photoUpdate.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-PhotoUpdate">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.photoUpdate.fields.booking') }}
                        </th>
                        <th>
                            {{ trans('cruds.photoUpdate.fields.photo') }}
                        </th>
                        <th>
                            {{ trans('cruds.photoUpdate.fields.comment') }}
                        </th>
                        <th>
                            {{ trans('cruds.photoUpdate.fields.user') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($photoUpdates as $key => $photoUpdate)
                        <tr data-entry-id="{{ $photoUpdate->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $photoUpdate->booking->decline ?? '' }}
                            </td>
                            <td>
                                @if($photoUpdate->photo)
                                    <a href="{{ $photoUpdate->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $photoUpdate->photo->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $photoUpdate->comment ?? '' }}
                            </td>
                            <td>
                                {{ $photoUpdate->user->name ?? '' }}
                            </td>
                            <td>
                                @can('photo_update_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.photo-updates.show', $photoUpdate->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('photo_update_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.photo-updates.edit', $photoUpdate->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('photo_update_delete')
                                    <form action="{{ route('admin.photo-updates.destroy', $photoUpdate->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('photo_update_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.photo-updates.massDestroy') }}",
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
  let table = $('.datatable-PhotoUpdate:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
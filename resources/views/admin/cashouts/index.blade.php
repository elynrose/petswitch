@extends('layouts.admin')
@section('content')
@can('cashout_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.cashouts.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.cashout.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.cashout.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Cashout">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.cashout.fields.user') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.last_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.cashout.fields.credits') }}
                        </th>
                        <th>
                            {{ trans('cruds.cashout.fields.amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.cashout.fields.issued') }}
                        </th>
                        <th>
                            {{ trans('cruds.cashout.fields.tracking') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cashouts as $key => $cashout)
                        <tr data-entry-id="{{ $cashout->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $cashout->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $cashout->user->last_name ?? '' }}
                            </td>
                            <td>
                                {{ $cashout->credits ?? '' }}
                            </td>
                            <td>
                                {{ $cashout->amount ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $cashout->issued ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $cashout->issued ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ $cashout->tracking ?? '' }}
                            </td>
                            <td>
                                @can('cashout_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.cashouts.show', $cashout->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('cashout_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.cashouts.edit', $cashout->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('cashout_delete')
                                    <form action="{{ route('admin.cashouts.destroy', $cashout->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('cashout_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.cashouts.massDestroy') }}",
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
  let table = $('.datatable-Cashout:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
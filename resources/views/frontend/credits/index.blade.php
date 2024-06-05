@extends('layouts.frontend')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">


                <div class="card-body">
                <h1 class="mb-5">
                    {{ trans('cruds.credit.title_singular') }} 
</h1>
                  <p class="mb-5 alert alert-info"><i class="fa fa-star"></i> You have  {{ $credits->points ?? '' }} Credits</p>

                  <h4>How to earn credits</h4>
                  <p>You can earn credits by the number of hours you care for other peoples pets.</p>
                
                <h4 class="mt-5">Invitation Code</h4>
                <p>Share your invitation code with friends and earn credits when they sign up.</p>
                <p>Your invitation code is: <strong>{{ Auth::user()->invitation_code ?? '' }}</strong></p>
                
                <h4 class="mt-5">Referral Link</h4>
                <p>Share your referral link with friends and earn 3 credits when they sign up. That's three hours of free pet care.</p>
                <p>Your referral link is: <strong>{{ route('register') }}?referral={{ Auth::user()->invitation_code ?? '' }}</strong></p>
                <h4 class="mt-5 mb-5"> Sample letter to send to your friends</h4>
                <div class="bg-light p-4">
                        <p>Dear [Friend's Name],</p>
    <p>I hope this message finds you and your furry friend well!</p>
    <p>I wanted to share something amazing with you – I've joined <strong>{{ trans('panel.site_title') }}</strong>, a wonderful community of pet lovers dedicated to providing safe and loving care for our pets. It's been such a relief knowing that my pet is in good hands whenever I need help.</p>
    <p>Here's why I think you'll love it too:</p>
    <ul>
        <li><strong>Earn and Use Credits:</strong> Care for other pets and earn credits, which you can use when you need care for your own pet.</li>
        <li><strong>Verified Members:</strong> All members go through a verification process to ensure trust and safety.</li>
        <li><strong>Multiple Care Options:</strong> Choose from day care, boarding, or play dates for your pet.</li>
    </ul>
    <p>I think you and your furry friend will really benefit from this supportive community. Plus, it's a great way to meet other pet owners and share our love for our furry family members.</p>
    <p>You can sign up using this link: <a href="{{ route('register') }}?referral={{ Auth::user()->invitation_code ?? '' }}">{{ route('register') }}?referral={{ Auth::user()->invitation_code ?? '' }}</a></p>
    <p>Looking forward to seeing you and furry friend on {{ trans('panel.site_title') }}!</p>
    <p>Warm regards,</p>
    <p>{{ Auth::user()->name }} {{ Auth::user()->last_name }}<br>
    </p>
</div>
            </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('credit_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('frontend.credits.massDestroy') }}",
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
  let table = $('.datatable-Credit:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
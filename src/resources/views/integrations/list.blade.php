@extends('web::layouts.grids.4-8', ['viewname' => 'integrations'])

@section('title', trans_choice('notifications::notifications.integration', 2))
@section('page_header', trans_choice('notifications::notifications.integration', 2))

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('notifications::notifications.new_integration') }}</h3>
    </div>
    <div class="card-body">

      <p>
        {{ trans('notifications::notifications.new_integration_message') }}
      </p>

      <p>

        <a href="{{ route('notifications.integrations.new.discord') }}" class="btn btn-primary btn-block">
          <i class="fab fa-discord"></i>
          {{ trans('notifications::notifications.new_discord') }}
        </a>
        <a href="{{ route('notifications.integrations.new.email') }}" class="btn btn-primary btn-block">
          <i class="fas fa-envelope"></i>
          {{ trans('notifications::notifications.new_email') }}
        </a>
        <a href="{{ route('seatcore::notifications.integrations.new.slack') }}" class="btn btn-primary btn-block">
          <i class="fab fa-slack"></i>
          {{ trans('notifications::notifications.new_slack') }}
        </a>

      </p>

    </div>
  </div>

@stop

@section('right')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('notifications::notifications.configured_integrations') }}</h3>
    </div>
    <div class="card-body">

      <table class="table compact table-condensed table-hover" id="integrations">
        <thead>
          <tr>
            <th>{{ trans('notifications::notifications.name') }}</th>
            <th>{{ trans('notifications::notifications.type') }}</th>
            <th>{{ trans('notifications::notifications.settings') }}</th>
            <th></th>
          </tr>
        </thead>
      </table>

    </div>
  </div>
@stop

@push('javascript')

<script>

  $(function () {
    $('table#integrations').DataTable({
      processing: true,
      serverSide: true,
      ajax      : '{{ route('seatcore::notifications.integrations.list.data') }}',
      columns   : [
        {data: 'name', name: 'name'},
        {data: 'type', name: 'type'},
        {
          // Settings need to be passed through a render function as its
          // actually a json string in the database.
          data: 'settings', name: 'settings', searchable: false, render: function (data) {

          // Prepare a string to return.
          var return_string = '';

          // Loop over the configuration and prep the return string
          for (var k in data) {
            if (data.hasOwnProperty(k)) {
              return_string = return_string + k + ': ' + data[k] + '<br>';
            }
          }

          return return_string;
        }
        },
        {data: 'actions', name: 'actions', searchable: false, sortable: false}
      ]
    });
  });

</script>

@endpush

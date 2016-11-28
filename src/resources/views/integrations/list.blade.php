@extends('web::layouts.grids.4-8', ['viewname' => 'integrations'])

@section('title', trans_choice('notifications::notifications.integration', 2))
@section('page_header', trans_choice('notifications::notifications.integration', 2))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('notifications::notifications.new_integration') }}</h3>
    </div>
    <div class="panel-body">

      <p>
        {{ trans('notifications::notifications.new_integration_message') }}
      </p>

      <p>

        <a href="{{ route('notifications.integrations.new.email') }}" class="btn btn-primary btn-block">
          {{ trans('notifications::notifications.new_email') }}
        </a>
        <a href="{{ route('notifications.integrations.new.slack') }}" class="btn btn-primary btn-block">
          {{ trans('notifications::notifications.new_slack') }}
        </a>

      </p>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('notifications::notifications.configured_integrations') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="integrations">
        <thead>
        <tr>
          <th>{{ trans('notifications::notifications.name') }}</th>
          <th>{{ trans('notifications::notifications.type') }}</th>
          <th>{{ trans('notifications::notifications.settings') }}</th>
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
      ajax      : '{{ route('notifications.integrations.list.data') }}',
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
        {data: 'actions', name: 'actions', searchable: false},
      ]
    });
  });

</script>

@endpush

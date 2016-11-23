@extends('web::layouts.grids.4-8', ['viewname' => 'integrations'])

@section('title', trans('web::seat.integrations'))
@section('page_header', trans('web::seat.integrations'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">New Integration</h3>
    </div>
    <div class="panel-body">

      <p>
        Add a new notifications integration.
      </p>

      <p>

        <a href="{{ route('notifications.integrations.new.email') }}" class="btn btn-primary btn-block">
          New Email Integration
        </a>
        <a href="{{ route('notifications.integrations.new.slack') }}" class="btn btn-primary btn-block">
          New Slack Integration
        </a>

      </p>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Configured Integrations</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="integrations">
        <thead>
        <tr>
          <th>Name</th>
          <th>Type</th>
          <th>Settings</th>
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
      ajax: '{{ route('notifications.integrations.list.data') }}',
      columns: [
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
            if(data.hasOwnProperty(k)) {
              return_string = return_string + k + ': ' + data[k] + '<br>';
            }
          }

          return return_string;
        }
        },
      ]
    });
  });

</script>

@endpush

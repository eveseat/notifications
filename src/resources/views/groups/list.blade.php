@extends('web::layouts.grids.4-8', ['viewname' => 'groups'])

@section('title', trans('web::seat.groups'))
@section('page_header', trans('web::seat.groups'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">New Notifications Group</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('notifications.groups.new.post') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="name">Group Name</label>
            <input type="text" name="name" class="form-control" id="username" value="{{ old('name') }}"
                   placeholder="Group Name">
          </div>

          <div class="form-group">
            <label for="text">Group Type</label>
            <select name="type" class="form-control" id="notification-type">
              <option value="seat">SeAT</option>
              <option value="eve">Eve</option>
              <option value="char">Character</option>
              <option value="corp">Corp</option>
            </select>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            Add
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Notifications Groups</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive" id="groups">
        <thead>
        <tr>
          <th>Name</th>
          <th>Type</th>
          <th>Alerts</th>
          <th>Integrations</th>
          <th>Affiliations</th>
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
    $('table#groups').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('notifications.groups.list.data') }}',
      columns: [
        {data: 'name', name: 'name'},
        {data: 'type', name: 'type'},
        {data: 'alerts', name: 'alerts', searchable: false},
        {data: 'integrations', name: 'integrations', searchable: false},
        {data: 'affiliations', name: 'affiliations', searchable: false},
        {data: 'actions', name: 'actions', searchable: false, orderable: false},
      ]
    });
  });

</script>

@endpush

@extends('web::layouts.grids.4-8', ['viewname' => 'groups'])

@section('title', trans_choice('notifications::notifications.group', 2))
@section('page_header', trans_choice('notifications::notifications.group', 2))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('notifications::notifications.new_group') }}</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('notifications.groups.new.post') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="name">{{ trans('notifications::notifications.group_name') }}</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                   placeholder="Group Name">
          </div>

          <div class="form-group">
            <label for="text">{{ trans('notifications::notifications.group_type') }}</label>
            <select name="type" class="form-control" id="notification-type">
              <option value="seat">SeAT</option>
              <option value="eve">Eve</option>
              <option value="char">Character</option>
              <option value="corp">Corporation</option>
            </select>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            {{ trans('notifications::notifications.add') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('notifications::notifications.group', 2) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive" id="groups">
        <thead>
        <tr>
          <th>{{ trans('notifications::notifications.name') }}</th>
          <th>{{ trans('notifications::notifications.type') }}</th>
          <th>{{ trans_choice('notifications::notifications.alert', 2) }}</th>
          <th>{{ trans_choice('notifications::notifications.integration', 2) }}</th>
          <th>{{ trans_choice('notifications::notifications.affiliation', 2) }}</th>
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
      ajax      : '{{ route('notifications.groups.list.data') }}',
      columns   : [
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

@extends('web::layouts.grids.4-8', ['viewname' => 'groups'])

@section('title', trans('web::seat.groups'))
@section('page_header', trans('web::seat.groups'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">New Notifications Group</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="#" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="name">Group Name</label>
            <input type="text" name="name" class="form-control" id="username" value="{{ old('name') }}"
                   placeholder="Group Name">
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

      <table class="table compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>ID</th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

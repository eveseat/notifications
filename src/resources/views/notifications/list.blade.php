@extends('web::layouts.grids.12', ['viewname' => 'notifications'])

@section('title', trans('web::seat.notifications'))
@section('page_header', trans('web::seat.notifications'))

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Notifications</h3>
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

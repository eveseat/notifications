@extends('web::layouts.grids.12', ['viewname' => 'notifications'])

@section('title', trans('web::seat.notifications'))
@section('page_header', trans('web::seat.notifications'))

@section('content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Notifications</h3>
    </div>
    <div class="card-body">

      <table class="table compact table-condensed table-hover">
        <thead>
        <tr>
          <th>ID</th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

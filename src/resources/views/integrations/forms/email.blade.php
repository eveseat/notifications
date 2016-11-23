@extends('web::layouts.grids.12', ['viewname' => 'integrations'])

@section('title', trans('web::seat.integrations'))
@section('page_header', trans('web::seat.integrations'))
@section('page_description', 'New Mail Integration')

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">New Email Integration</h3>
    </div>
    <div class="panel-body">

      <div class="col-md-4 col-md-offset-4">
        <form role="form" action="{{ route('notifications.integrations.new.email.add') }}" method="post">
          {{ csrf_field() }}

          <div class="box-body">

            <div class="form-group">
              <label for="name">Integration Name</label>
              <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                     placeholder="Name">
              <span class="help-block">
                A name with which this integration could be identified.
              </span>
            </div>

            <div class="form-group">
              <label for="email">Destination Email Address</label>
              <input type="text" name="email" class="form-control" id="email" value="{{ old('email') }}"
                     placeholder="Email">
              <span class="help-block">
                Please enter the email address where these
                notifications should be sent.
              </span>
            </div>

          </div><!-- /.box-body -->

          <div class="box-footer">
            <a href="{{ route('notifications.integrations.list') }}">
              Back to Integrations
            </a>
            <button type="submit" class="btn btn-primary pull-right">
              Add
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>

@stop

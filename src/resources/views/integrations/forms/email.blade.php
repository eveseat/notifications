@extends('web::layouts.grids.12', ['viewname' => 'integrations'])

@section('title', trans_choice('notifications::notifications.integration', 0))
@section('page_header', trans_choice('notifications::notifications.integration', 0))
@section('page_description', trans('notifications::notifications.new_email'))

@section('content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('notifications::notifications.new_email') }}</h3>
    </div>
    <div class="card-body">

      <div class="col-md-4 offset-md-4">
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
            <button type="submit" class="btn btn-primary float-right">
              Add
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>

@stop

@extends('web::layouts.grids.12', ['viewname' => 'integrations'])

@section('title', trans('web::seat.integrations'))
@section('page_header', trans('web::seat.integrations'))
@section('page_description', 'New Slack Integration')

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">New Slack Integration</h3>
    </div>
    <div class="panel-body">

      <div class="col-md-4 col-md-offset-4">
        <form role="form" action="{{ route('notifications.integrations.new.slack.add') }}" method="post">
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
              <label for="url">Webhook Url</label>
              <input type="text" name="url" class="form-control" id="url" value="{{ old('url') }}"
                     placeholder="URL: https://hooks.slack.com/services">
              <span class="help-block">
                You will need to configure an "Incoming Webhook" integration
                for your Slack team. This integration will provide you with a URL.
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

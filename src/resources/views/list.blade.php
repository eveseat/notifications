@extends('web::layouts.grids.12')

@section('title', 'Notifications')
@section('page_header', 'Notifications')

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Notifications
        <span class="pull-right">
          <a href="{{ route('notifications.clear') }}" class="btn btn-xs btn-danger confirmlink">Clear</a>
        </span>
      </h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Sent</th>
          <th>Subject</th>
          <th>Message</th>
        </tr>

        @foreach($notifications as $notification)

          <tr>
            <td>
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $notification->created_at }}">
              {{ human_diff($notification->created_at) }}
              </span>
            </td>
            <td>
              {{ $notification->subject }}
            </td>
            <td>{{ $notification->message }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

      <span class="pull-right">
        {!! $notifications->render() !!}
      </span>
    </div>
    <div class="panel-footer">
      {{ count($notifications) }} notifications
    </div>
  </div>

@stop

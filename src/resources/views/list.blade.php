@extends('web::layouts.grids.12')

@section('title', trans('web::seat.notifications'))
@section('page_header', trans('web::seat.notifications'))

@section('full')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.notifications') }}
        <span class="pull-right">
          <a href="{{ route('notifications.clear') }}" class="btn btn-xs btn-danger confirmlink">Clear</a>
        </span>
      </h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.date') }}</th>
          <th>{{ trans('web::seat.subject') }}</th>
          <th>{{ trans('web::seat.message') }}</th>
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
      {{ count($notifications) }} {{ trans('web::seat.notifications') }}
    </div>
  </div>

@stop

<div class="d-flex flex-column">
    <a href="{{ route('seatcore::notifications.integrations.delete', ['integration_id' => $row->id]) }}"
       type="button" class="btn btn-danger btn-xs confirmlink m-1">
        {{ trans('web::seat.delete') }}
    </a>
    <a href="{{ route('seatcore::notifications.integrations.test', ['integration_id' => $row->id]) }}"
       type="button" class="btn btn-primary btn-xs m-1">
      {{ trans('notifications::notifications.test_integration') }}
    </a>
</div>

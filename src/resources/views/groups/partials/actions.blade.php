<div class="btn-group pull-right">
  <a href="{{ route('notifications.groups.edit', ['notification_group_id' => $row->id]) }}"
     type="button" class="btn btn-primary btn-xs">
    {{ trans('web::seat.edit') }}
  </a>
  <a href="{{ route('notifications.groups.delete', ['group_id' => $row->id]) }}"
     type="button" class="btn btn-danger btn-xs confirmlink">
    {{ trans('web::seat.delete') }}
  </a>
</div>

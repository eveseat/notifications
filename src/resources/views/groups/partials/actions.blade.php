<div class="btn-group btn-group-sm float-right">
  <a href="{{ route('seatcore::notifications.groups.edit', ['notification_group_id' => $row->id]) }}"
     type="button" class="btn btn-warning btn-xs">
    <i class="fas fa-pencil-alt"></i>
    {{ trans('web::seat.edit') }}
  </a>
  <a href="{{ route('seatcore::notifications.groups.delete', ['group_id' => $row->id]) }}"
     type="button" class="btn btn-danger btn-xs confirmlink">
    <i class="fas fa-trash-alt"></i>
    {{ trans('web::seat.delete') }}
  </a>
</div>

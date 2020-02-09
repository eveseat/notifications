@extends('web::layouts.grids.4-8', ['viewname' => 'groups'])

@section('title', trans_choice('notifications::notifications.group', 2))
@section('page_header', trans_choice('notifications::notifications.group', 2))

@section('left')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans('notifications::notifications.new_group') }}</h3>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('notifications.groups.new.post') }}" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <label for="name">{{ trans('notifications::notifications.group_name') }}</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}"
                   placeholder="Group Name">
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            {{ trans('notifications::notifications.add') }}
          </button>
        </div>
      </form>

    </div>
  </div>

@stop

@section('right')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('notifications::notifications.group', 2) }}</h3>
    </div>
    <div class="card-body">

      {!! $dataTable->table() !!}

    </div>
  </div>

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush

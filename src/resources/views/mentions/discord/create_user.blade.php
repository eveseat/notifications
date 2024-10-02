@extends('web::layouts.grids.12', ['viewname' => 'integrations'])

@section('title', trans_choice('notifications::notifications.create_mention', 0))
@section('page_header', trans_choice('notifications::notifications.create_mention', 0))
@section('page_description', trans('notifications::notifications.create_discord_user_mention'))

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans('notifications::notifications.create_discord_user_mention') }}</h3>
        </div>
        <div class="card-body">

            <div class="col-md-4 offset-md-4">
                <form role="form" action="{{ route('seatcore::notifications.mentions.edit.discord.user.add') }}" method="post">
                    {{ csrf_field() }}

                    <input type="hidden" name="group_id" value="{{$group_id}}">

                    <div class="box-body">

                        <div class="form-group">
                            <label for="role_id">{{ trans("notifications::notifications.discord_user_id") }}</label>
                            <input type="number" name="user_id" class="form-control" id="user_id" value="{{ old('role_id') }}" placeholder="{{ trans("notifications::notifications.discord_user_id") }}">
                            <span class="help-block">
                                {{ trans("notifications::notifications.discord_user_id_help") }}
                            </span>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary float-right">
                            {{ trans("notifications::notifications.add") }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

@stop

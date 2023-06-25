@extends('web::layouts.grids.4-4-4')

@section('title', trans('notifications::notifications.edit_group'))
@section('page_header', trans('notifications::notifications.edit_group'))
@section('page_description', $group->name)

@section('left')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans_choice('notifications::notifications.integration', 2) }}</h3>
        </div>
        <div class="card-body">

            <form role="form" action="{{ route('seatcore::notifications.groups.edit.integration.add') }}" method="post">
                {{ csrf_field() }}
                <input name="id" value="{{ $group->id }}" type="hidden">

                <div class="box-body">

                    <div class="form-group">
                        <label for="username">{{ trans_choice('notifications::notifications.integration', 1) }}</label>
                        <select name="integrations[]" id="integration" class="form-control" multiple>

                            @foreach($integrations as $integration)

                                <option value="{{ $integration->id }}">
                                    {{ $integration->name }} ({{ $integration->type }})
                                </option>

                            @endforeach

                        </select>
                    </div>

                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">
                        {{ trans('notifications::notifications.add') }}
                    </button>
                </div>
            </form>

            <table class="table compact table-condensed table-hover">
                <thead>
                <tr>
                    <th>{{ trans('notifications::notifications.name') }}</th>
                    <th>{{ trans('notifications::notifications.type') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                @foreach($group->integrations as $integration)

                    <tr>
                        <td>{{ $integration->name }}</td>
                        <td>{{ ucfirst($integration->type) }}</td>
                        <td>
                            <a href="{{ route('seatcore::notifications.groups.edit.integration.delete', ['group_id' => $group->id, 'integration_id' => $integration->id]) }}"
                               class="btn btn-xs btn-danger pull-right">
                                {{ trans('web::seat.delete') }}
                            </a>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

        </div>
        <div class="card-footer">
            {{ count($group->integrations) }}
            {{ trans_choice('notifications::notifications.integration', count($group->integrations)) }}
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans_choice('notifications::notifications.mention', 2) }}</h3>
        </div>
        <div class="card-body">
            <form role="form" action="{{ route('seatcore::notifications.groups.edit.mention.add') }}" method="post">
                {{ csrf_field() }}
                <input name="id" value="{{ $group->id }}" type="hidden">

                <div class="box-body">
                    <div class="form-group">
                        <label for="mention">{{ trans_choice('notifications::notifications.mention', 1) }}</label>
                        <select name="mention_type" id="mention" class="form-control">
                            @foreach(config("notifications.mentions") as $mention_type=>$mention)
                                <option value="{{ $mention_type }}">
                                        {{ ucfirst($mention["type"]) }}: {{ trans($mention["label"]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">
                        {{ trans('notifications::notifications.add') }}
                    </button>
                </div>
            </form>

            <table class="table compact table-condensed table-hover">
                <thead>
                <tr>
                    <th>{{ trans('notifications::notifications.name') }}</th>
                    <th>{{ trans('notifications::notifications.type') }}</th>
                    <th>{{ trans('notifications::notifications.data') }}</th>
                    <th>{{ trans('notifications::notifications.actions') }}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($group->mentions as $mention)

                    <tr>
                        <td>{{ trans($mention->getType()->name) }}</td>
                        <td>{{ ucfirst($mention->getType()->type) }}</td>
                        <th>
                            @if($mention->data !== [])
                                <code>{{ json_encode($mention->data) }}</code>
                            @endif
                        </th>
                        <td>
                            <a href="{{ route('seatcore::notifications.groups.edit.mention.delete', ['mention_id' => $mention->id]) }}"
                               class="btn btn-xs btn-danger pull-right">
                                {{ trans('web::seat.delete') }}
                            </a>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>
        </div>
    </div>

@stop

@section('center')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans_choice('notifications::notifications.alert', 2) }}</h3>
        </div>
        <div class="card-body">

            <form role="form" action="{{ route('seatcore::notifications.groups.edit.alert.add_all') }}"
                  method="post" id="group-alerts-all-form">
                {{ csrf_field() }}
                <input name="id" value="{{ $group->id }}" type="hidden">
            </form>

            <form role="form" action="{{ route('seatcore::notifications.groups.edit.alert.add') }}"
                  method="post" id="group-alerts-selection-form">
                {{ csrf_field() }}
                <input name="id" value="{{ $group->id }}" type="hidden">

                <div class="box-body">

                    <div class="form-group">
                        <label for="alerts">Alert</label>
                        <select name="alerts[]" id="alerts" class="form-control" multiple></select>
                    </div>

                </div><!-- /.box-body -->
            </form>

            <div class="box-footer">
                <button type="submit" class="btn btn-success" form="group-alerts-all-form">
                    {{ trans('notifications::notifications.add_all_alerts') }}
                </button>
                <button type="submit" class="btn btn-primary pull-right" form="group-alerts-selection-form">
                    {{ trans('notifications::notifications.add') }}
                </button>
            </div>

            <table class="table compact table-condensed table-hover">
                <thead>
                <tr>
                    <th>{{ trans('notifications::notifications.name') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                @foreach($group->alerts as $alert)

                    <tr>
                        <td>
                            {{ trans(config('notifications.alerts.' . $alert->alert . '.label', $alert->alert)) }}
                        </td>
                        <td>
                            <a href="{{ route('seatcore::notifications.groups.edit.alert.delete', ['group_id' => $group->id, 'alert_id' => $alert->id]) }}"
                               class="btn btn-xs btn-danger pull-right">
                                {{ trans('web::seat.delete') }}
                            </a>
                        </td>
                    </tr>

                @endforeach

                </tbody>
            </table>

        </div>
        <div class="card-footer">
            {{ count($group->alerts) }}
            {{ trans_choice('notifications::notifications.alert', count($group->alerts)) }}
        </div>
    </div>


@stop

@section('right')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans_choice('notifications::notifications.affiliation', 2) }}</h3>
        </div>
        <div class="card-body">

            <form role="form" action="{{ route('seatcore::notifications.groups.edit.affiliations.add') }}"
                  method="post">
                {{ csrf_field() }}
                <input name="id" value="{{ $group->id }}" type="hidden">

                <div class="form-group">
                    <label for="corporations">{{ trans('web::seat.available_corporations') }}</label>
                    <select name="corporations[]" id="available_corporations" style="width: 100%" multiple>

                        @foreach($all_corporations as $corporation)
                            <option value="{{ $corporation->corporation_id }}">
                                {{ $corporation->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label for="characters">{{ trans('web::seat.available_characters') }}</label>
                    <select name="characters[]" id="available_characters" style="width: 100%" multiple>

                        @foreach($all_characters as $character)
                            <option value="{{ $character->character_id }}">
                                {{ $character->name }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <button type="submit" class="btn btn-success btn-block">
                    {{ trans('web::seat.add_affiliations') }}
                </button>

            </form>

            @if($group->affiliations->count() > 0)

                <table class="table table-hover table-condensed">
                    <tbody>

                    <tr>
                        <th colspan="4"
                            class="text-center">{{ trans('web::seat.current_affiliations') }}</th>
                    </tr>

                    @foreach($group->affiliations as $affiliation)

                        <tr>
                            <td>
                                @switch($affiliation->type)
                                    @case('char')
                                    @include('web::partials.character', ['character' => $affiliation->entity])
                                    @break
                                    @case('corp')
                                    @include('web::partials.corporation', ['corporation' => $affiliation->entity])
                                    @break
                                    @default
                                    <span>{{ trans('web::seat.unknown') }}</span>
                                @endswitch
                            </td>
                            <td>{{ ucfirst($affiliation->type) }}</td>
                            <td>
                                <a href="{{ route('seatcore::notifications.groups.edit.affiliation.delete', ['group_id' => $group->id, 'affiliation_id' => $affiliation->id]) }}"
                                   type="button" class="btn btn-danger btn-xs pull-right">
                                    {{ trans('web::seat.remove') }}
                                </a>
                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>

            @else

                <p>
                    {{ trans('notifications::notifications.no_affiliation_notice') }}
                </p>

            @endif

        </div>
    </div>

@stop

@push('javascript')

    @include('web::includes.javascript.id-to-name')

    <script type="text/javascript">
        $('select#integration, select#mention, select#available_corporations, select#available_characters').select2();

        $('select#alerts').select2({
            ajax: {
                url: '{{ route('seatcore::notifications.ajax.alerts') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data,
                    };
                },
                cache: true
            },
            templateResult: function (data) {
                if (data.loading)
                    return data.text;

                if (!data.channels)
                    return data.label;

                icons = [];
                data.channels.forEach(handler => handler === 'mail' ?
                    icons.push('<i class="fas fa-envelope"></i>') : icons.push(`<i class="fab fa-${handler}"></i>`));

                return $(`<div class="select2-result-alert clearfix">${data.label} ${icons.join(' ')}</div>`);
            },
            templateSelection: function (data) {
                return data.id;
            }
        });

        ids_to_names();

    </script>

@endpush

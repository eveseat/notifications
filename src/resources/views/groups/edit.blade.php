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

      <form role="form" action="{{ route('notifications.groups.edit.integration.add') }}" method="post">
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
              <a href="{{ route('notifications.groups.edit.integration.delete', ['group_id' => $group->id, 'integration_id' => $integration->id]) }}"
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


@stop

@section('center')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('notifications::notifications.alert', 2) }}</h3>
    </div>
    <div class="card-body">

      <form role="form" action="{{ route('notifications.groups.edit.alert.add') }}" method="post">
        {{ csrf_field() }}
        <input name="id" value="{{ $group->id }}" type="hidden">
        <input name="type" value="{{ $group->type }}" type="hidden">

        <div class="box-body">

          <div class="form-group">
            <label for="alerts">Alert</label>
            <select name="alerts[]" id="alerts" class="form-control" multiple>

              @foreach(config('notifications.alerts.' . $group->type) as $name => $class)

                <option value="{{ $name }}">{{ $class['name'] }}</option>

              @endforeach

            </select>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <a href="#" class="btn btn-success">
            {{ trans('notifications::notifications.add_all_alerts') }}
          </a>
          <button type="submit" class="btn btn-primary pull-right">
            {{ trans('notifications::notifications.add') }}
          </button>
        </div>
      </form>

      <table class="table compact table-condensed table-hover">
        <thead>
          <tr>
            <th>{{ trans('notifications::notifications.name') }}</th>
          </tr>
        </thead>
        <tbody>

        @foreach($group->alerts as $alert)

          <tr>
            <td>
              {{ config('notifications.alerts.' . $group->type . '.' . $alert->alert . '.name') }}
            </td>
            <td>
              <a href="{{ route('notifications.groups.edit.alert.delete', ['group_id' => $group->id, 'alert_id' => $alert->id]) }}"
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
      <span class="pull-right">{{ ucfirst($group->type) }}</span>
    </div>
  </div>


@stop

@section('right')

  {{-- only show affiliations for char & corp types --}}
  @if($group->type === 'char' || $group->type == 'corp')

    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ trans_choice('notifications::notifications.affiliation', 2) }}</h3>
      </div>
      <div class="card-body">

        <form role="form" action="{{ route('notifications.groups.edit.affiliations.add') }}" method="post">
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
              <th colspan="4" class="text-center">{{ trans('web::seat.current_affiliations') }}</th>
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
                  <a href="{{ route('notifications.groups.edit.affiliation.delete', ['group_id' => $group->id, 'affiliation_id' => $affiliation->id]) }}"
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

  @endif

@stop

@push('javascript')

@include('web::includes.javascript.id-to-name')

<script type="text/javascript">

  $("select#integration, " +
      "select#alerts, " +
      "select#available_corporations, " +
      "select#available_characters").select2();

  ids_to_names();

</script>

@endpush

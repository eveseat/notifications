@extends('web::layouts.grids.4-4-4')

@section('title', trans('web::seat.edit_group'))
@section('page_header', trans('web::seat.edit_group'))
@section('page_description', $group->name)

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Integrations</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('notifications.groups.edit.integration.add') }}" method="post">
        {{ csrf_field() }}
        <input name="id" value="{{ $group->id }}" type="hidden">

        <div class="box-body">

          <div class="form-group">
            <label for="username">Integration</label>
            <select name="integrations[]" id="integration" class="form-control" multiple>

              @foreach($integrations as $integration)

                <option value="{{ $integration->id }}">{{ $integration->name }}</option>

              @endforeach

            </select>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right">
            Add
          </button>
        </div>
      </form>

      <table class="table compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>Name</th>
          <th>Type</th>
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
                Delete
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($group->integrations) }} Integrations
    </div>
  </div>


@stop

@section('center')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Alerts</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="{{ route('notifications.groups.edit.alert.add') }}" method="post">
        {{ csrf_field() }}
        <input name="id" value="{{ $group->id }}" type="hidden">
        <input name="type" value="{{ $group->type }}" type="hidden">

        <div class="box-body">

          <div class="form-group">
            <label for="alerts">Alert</label>
            <select name="alerts[]" id="alerts" class="form-control" multiple>

              @foreach(config('notifications.alerts.' . $group->type) as $name => $class)

                <option value="{{ $name }}">{{ class_basename($class) }}</option>

              @endforeach

            </select>
          </div>

        </div><!-- /.box-body -->

        <div class="box-footer">
          <a href="#" class="btn btn-success">
            Add All Alerts
          </a>
          <button type="submit" class="btn btn-primary pull-right">
            Add
          </button>
        </div>
      </form>

      <table class="table compact table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>Name</th>
        </tr>
        </thead>
        <tbody>

        @foreach($group->alerts as $alert)

          <tr>
            <td>{{ $alert->alert }}</td>
            <td>
              <a href="{{ route('notifications.groups.edit.alert.delete', ['group_id' => $group->id, 'alert_id' => $alert->id]) }}"
                 class="btn btn-xs btn-danger pull-right">
                Delete
              </a>
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($group->alerts) }} Alerts
      <span class="pull-right">{{ ucfirst($group->type) }}</span>
    </div>
  </div>


@stop

@section('right')

  {{-- only show affiliations for char & corp types --}}
  @if($group->type === 'char' || $group->type == 'corp')

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Affiliations</h3>
      </div>
      <div class="panel-body">

        <form role="form" action="{{ route('notifications.groups.edit.affiliations.add') }}" method="post">
          {{ csrf_field() }}
          <input name="id" value="{{ $group->id }}" type="hidden">

          <div class="form-group">
            <label for="corporations">{{ trans('web::seat.available_corporations') }}</label>
            <select name="corporations[]" id="available_corporations" style="width: 100%" multiple>

              @foreach($all_corporations as $corporation)
                <option value="{{ $corporation->corporationID }}">
                  {{ $corporation->corporationName }}
                </option>
              @endforeach

            </select>
          </div>

          <div class="form-group">
            <label for="characters">{{ trans('web::seat.available_characters') }}</label>
            <select name="characters[]" id="available_characters" style="width: 100%" multiple>

              @foreach($all_characters as $character)
                <option value="{{ $character->characterID }}">
                  {{ $character->characterName }}
                </option>
              @endforeach

            </select>

          </div>

          <button type="submit" class="btn btn-success btn-block">
            {{ trans('web::seat.add_affiliations') }}
          </button>

        </form>

        <table class="table table-hover table-condensed">
          <tbody>

          <tr>
            <th colspan="4" class="text-center">{{ trans('web::seat.current_affiliations') }}</th>
          </tr>

          @foreach($group->affiliations as $affiliation)

            <tr>
              <td>
                {!! img('auto', $affiliation->affiliation_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                <span rel="id-to-name">{{ $affiliation->affiliation_id }}</span>
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

      </div>
    </div>

  @endif

@stop

@push('javascript')

@include('web::includes.javascript.id-to-name')

<script>

    $("select#integration, " +
        "select#alerts, " +
        "select#available_corporations, " +
        "select#available_characters").select2();

</script>

@endpush

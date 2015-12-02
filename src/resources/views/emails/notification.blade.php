@extends('web::emails.layouts.email')

@section('content')

  <h4>Hi!</h4>
  <h4>{{ $data->subject }}</h4>
  <h3>{{ $data->message }}</h3>

@stop

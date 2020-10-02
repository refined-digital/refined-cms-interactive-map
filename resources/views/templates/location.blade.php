@extends('layouts.index')

@section('template')
  @include('templates.includes.map')
  @include('templates.includes.content')
@stop

@section('scripts')
  @include('templates.includes.map-scripts')
@append

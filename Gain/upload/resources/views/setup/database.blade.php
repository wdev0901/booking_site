@extends('setup.layouts.install')

@section('title', trans('lang.install'))

@section('content')
    <app-database-wizard></app-database-wizard>

@endsection
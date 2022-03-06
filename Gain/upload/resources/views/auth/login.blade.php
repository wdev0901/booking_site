@extends('layouts.publicapp')

@section('title', trans('lang.login').' '.'-')

@section('content')

    <login-form copyright_text="{{ $copyright_text }}" checkemail="{{$email}}" checkpass="{{$password}}"></login-form>

@endsection
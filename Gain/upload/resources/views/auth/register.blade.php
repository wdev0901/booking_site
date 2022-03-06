@extends('layouts.publicapp')

@section('title', trans('lang.register').' '.'-')

@section('content')

    <register-form emailadd="{{$email}}"
                   token="{{$token}}"
                   copyright_text="{{ $copyright_text }}">
    </register-form>

@endsection


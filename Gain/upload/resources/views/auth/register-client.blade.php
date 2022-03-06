@extends('layouts.publicapp')

@section('title', trans('lang.sign_up').' '.'-')

@section('content')

    <register-form copyright_text="{{ $copyright_text }}"></register-form>

@endsection
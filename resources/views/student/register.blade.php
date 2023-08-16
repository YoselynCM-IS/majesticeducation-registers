@extends('layouts.app')

@section('content')
    <pre-register-component :registers="{{$schools}}" :tipo="'externo'" sistema="{{$sistema}}"></pre-register-component>
@endsection

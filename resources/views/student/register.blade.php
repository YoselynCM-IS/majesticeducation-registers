@extends('layouts.app')

@section('content')
    <pre-register-component :registers="{{$schools}}" :tipo="'externo'"></pre-register-component>
@endsection

@extends('layouts.app')

@section('content')
   <emails-component sistemname="{{ env('APP_NAME') }}"></emails-component>
@endsection
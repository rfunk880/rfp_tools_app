@extends('layouts.mail')
@section('content')

Dear {{ $user->name }} <br/> <br/>

Your password to log into your RFP Management account (<a href="{{ url('/') }}" target="_blank">{{url('/')}}</a>)
<br/><br/>
<h4 style="font-weight: bold; font-family: Arial, Helvetica, sans-serif">{{ $password }}</h4>
<br/>

Your username will be your email address, Once logged in, please create a permanent password you will use moving forward. Let us know if you need any help.

@stop

@extends('layouts.mail')
@section('content')

Hello {{ $user->name }}, <br/>
<strong>Welcome to SWFunk Industrial Contractors!</strong> <br/>
Below you will find a temporary password to initially log into your account.
<h4 style="font-weight: bold; font-family: Arial, Helvetica, sans-serif">{{ $password }}</h4>

<br/>
Your username will be your email address, Once logged in, please create a permanent password you will use moving forward. Let us know if you need any help.
Please <a href="{{route('login')}}">click here</a> to login on the dashboard.

@stop
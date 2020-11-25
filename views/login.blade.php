@extends('layouts.app-clean')

@section('title', 'Login')

@section('content')

<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
    <!-- Nested Row within Card Body -->
    <div class="row">
        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
        <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
            @if (isset($error))
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endif
            </div>

            <form class="user" method="post">
                <div class="form-group">
                    <input type="login" class="form-control form-control-user" name="login" id="InputLogin" aria-describedby="loginHelp" placeholder="Enter Login..."
                        value="@if (isset($login)){{ $login }}@endif">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control form-control-user" name="password" id="InputPassword" placeholder="Password">
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox small">
                    <input type="checkbox" class="custom-control-input" id="customCheck">
                    <label class="custom-control-label" for="customCheck">Remember Me</label>
                    </div>
                </div>
                <input type="submit" value="Login" class="btn btn-primary btn-user btn-block">
            </form>

            <hr>

            <div class="text-center">
                <a class="small" href="/">Return to main page</a>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

@endsection
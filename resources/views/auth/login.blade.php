@extends('layouts.auth_master')

@section('title')
    Login
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
            <div class="card mt-3 mb-3">
                <div class="card-body">
                    <div class="row">
                        @include('layouts.partials.simple_alert_messages')
                        <div class="col-md-12 mb-3">
                            <h2>Sign In</h2>
                            <p>Enter your email and password to login</p>
                        </div>

                        {!! Form::open(['url' => route('login.perform')]) !!}
                        <div class="col-md-12">
                            <div class="mb-3">
                                {{ Form::label('email', 'Email', ['class' => 'form-label']) }}
                                {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'true']) }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-4">
                                {{ Form::label('password', 'Password', ['class' => 'form-label']) }}
                                {{ Form::password('password', ['class' => 'form-control', 'required' => 'true']) }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <div class="form-check form-check-primary form-check-inline">
                                    {{ Form::checkbox('remember', null, false, ['class' => 'form-check-input me-3', 'id' => 'remember']) }}
                                    {{ Form::label('remember', 'Remember me', ['class' => 'form-check-label']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-4">
                                {{ Form::submit('SIGN IN', ['class' => 'btn btn-secondary w-100']) }}
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

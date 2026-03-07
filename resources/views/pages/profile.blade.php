@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('My Profile') }}</div>

                <div class="card-body">
                    <h5>{{ auth()->user()->name }}</h5>
                    <p>{{ auth()->user()->email }}</p>
                    
                    <hr>
                    
                    <div class="mt-4">
                        <a href="{{ route('reservations') }}" class="btn btn-primary">
                            {{ __('My Reservations') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

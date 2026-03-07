@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('My Reservations') }}</div>

                <div class="card-body">
                    @if(auth()->user()->reservations->isEmpty())
                        <p class="text-center text-muted">{{ __('No reservations yet') }}</p>
                        <div class="text-center">
                            <a href="{{ route('voyages') }}" class="btn btn-primary">
                                {{ __('Browse Trips') }}
                            </a>
                        </div>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Trip') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(auth()->user()->reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->trip->departure }} - {{ $reservation->trip->arrival }}</td>
                                        <td>{{ $reservation->trip->departure_date }}</td>
                                        <td>{{ $reservation->status }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-info">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

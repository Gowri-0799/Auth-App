
@extends('layouts.admin')

@section('title', 'Subscription Hosted Page')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Hosted Page Information</h2>
    <p>Hosted Page ID: {{ $hostedPageId }}</p>

    <a href="https://subscriptions.zoho.com/hostedpage/{{ $hostedPageId }}" class="btn btn-primary">
        Go to Hosted Page
    </a>
</div>
@endsection

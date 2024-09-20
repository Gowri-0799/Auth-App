@extends("layouts.default")
@section('title', "Plans")
@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Available Plans</h2>
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('plans.store') }}" method="POST">
            @csrf
            <label for="name">Plan Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" required>

            <button type="submit">Create Plan</button>
        </form>
    </div>

@endsection

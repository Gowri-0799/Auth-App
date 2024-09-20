@extends("layouts.default")
@section('title', "Tokens")
@section('content')
    <div class="row mt-5">

        <div class="col-md-6 offset-md-3 text-center">
            <!-- If there's an access token in the session, display it -->
            @if(session('accessToken'))
                <p>Generated Access Token: <strong>{{ session('accessToken') }}</strong></p>
            @endif

            <!-- Button to generate the access token -->
            <form action="{{ route('generate.access.token') }}" method="POST">
                @csrf
                <button type="submit" class="btn custom-btn" style="background-color: #0c4128; color: white; border: none;">Generate Access Token</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (Optional if you use JS components like modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQoHqONlYzZvvm+HGmT7HrwEXLwF5u2e6g7jnpfl5QUjFtbWWp5T6AbwLpPZnKkM" crossorigin="anonymous"></script>
@endsection

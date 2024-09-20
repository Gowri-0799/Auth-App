@extends("layouts.default")
@section('title', "Admin Home")
@section('content')
    <div class="container">
        <div style="position: relative; top: -20px">&nbsp;</div>
        <div style="display: flex; justify-content: flex-end">
            <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                <nav class="-mx-3 flex flex-1 justify-end">
                    <a
                        href="{{ route('logout') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Logout
                    </a>
                </nav>
            </header>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <h3>Welcome to Admin Dashboard</h3>
                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

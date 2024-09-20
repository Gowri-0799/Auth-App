@extends("layouts.default")
@section('title', "Index")
@section('content')
    <div class="container">
        <div style="position: relative; top: -20px">&nbsp;</div>
        <div style="display: flex; justify-content: flex-end">
            <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                <nav class="-mx-3 flex flex-1 justify-end">
                    <a
                        href="{{ route('login') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Log in
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Register
                    </a>
                </nav>

            </header>

            <main class="mt-6">

            </main>
    </div>
</div>
@endsection


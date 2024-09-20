@extends("layouts.default")
@section("title", "Register")
@section("content")

    <main class="mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div style="display: flex; justify-content: space-around">
                    <nav class=" bg-clearlink d-flex justify-content-between">
                        <div class="container-fluid">
                            <span class="navbar-brand p-1"><img width="150" height="37" src="/assets/images/cl_logo.svg"
                                                                alt="Clearlink Logo"></span>
                        </div>
                    </nav>
                    <nav class="-mx-3 flex flex-1 justify-end">
                        <a
                            href="{{ route('login') }}"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Back
                        </a>
                    </nav>
                </div>
                <div class="d-flex flex-column justify-content-center align-items-center pt-5">
                    <h4 class="text-primary mt-5 mb-3">Clearlink ISP Billing Admin Portal</h4>
                </div>
                <div class="col-md-4">
                    @if(session()->has("success"))
                        <div class="alert alert-success">
                            {{session()->get("success")}}
                        </div>
                    @endif
                    @if(session()->has("error"))
                        <div class="alert alert-danger">
                            {{session()->get("error")}}
                        </div>
                    @endif
                    <div class="card">
                        <h3 class="card-header text-center">Register</h3>
                        <div class="card-body">
                            <form method="POST" action="{{route("register.post")}}">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="text" placeholder="Name" id="name" class="form-control" name="name"
                                           required autofocus>
                                    @if($errors->has('name'))
                                        <span class="text-danger">
                                            {{$errors->first('name')}}
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" placeholder="Email" id="email" class="form-control" name="email"
                                           required autofocus>
                                    @if($errors->has('email'))
                                        <span class="text-danger">
                                            {{$errors->first('email')}}
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <input type="password" placeholder="Password" id="password" class="form-control"
                                           name="password" required>
                                    @if($errors->has('password'))
                                        <span class="text-danger">
                                            {{$errors->first('password')}}
                                        </span>
                                    @endif
                                </div>
                                <div class="d-grid mx-auto">
                                    <button type="submit" class="btn btn-dark btn-block">Sign up</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

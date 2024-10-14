@extends("layouts.login")
@section('title', "Login")
@section("content")
    <main class="mt-5">
        <div class="container">

            <div class="row justify-content-center">
                <div style="display: flex; justify-content: space-around">
                    <nav class=" bg-clearlink d-flex justify-content-between">
                        <div class="container-fluid">
                            <span class="navbar-brand p-1"><img width="150" height="37" src="/assets/images/testlogo.png"
                                                                alt="Clearlink Logo"></span>
                        </div>
                    </nav>
                </div>
                <div class="d-flex flex-column justify-content-center align-items-center pt-5">
                    <h4 class="text-primary mt-5 mb-3">Testlink ISP Billing Admin Portal</h4>
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
                        <h3 class="card-header text-center">ADMIN LOGIN</h3>
                        <div class="card-body">
                            <form method="POST" action="{{route("admin.post")}}">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="text" placeholder="email" id="email" class="form-control" name="email"
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
                                    <button type="submit" class="btn btn-dark btn-block">Signin</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

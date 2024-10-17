@extends("layouts.default")
@section('title', "Plans")
@section('content')
<div id="content" class="d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fc; margin-left: 240px; width: calc(100% - 240px);">

    <div class="container mt-5 d-flex justify-content-center">
        <!-- Centered Bootstrap card with light sky blue background -->
        <div class="card shadow-sm border-0 rounded-lg" style="width: 400px; background-color: #e0f7fa;">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Create a New Plan</h2>

                <!-- Success and Error messages -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form inside card -->
                <form action="{{ route('plans.store') }}" method="POST">
                    @csrf
                    <!-- Plan Name Field -->
                    <div class="mb-3">
                        <label for="plan_name" class="form-label font-weight-bold">Plan Name</label>
                        <input type="text" name="plan_name" id="plan_name" class="form-control" placeholder="Enter plan name" required>
                    </div>

                    <!-- Plan Price Field -->
                    <div class="mb-3">
                        <label for="plan_price" class="form-label font-weight-bold">Plan Price $</label>
                        <input type="number" name="plan_price" id="plan_price" class="form-control" step="0.01" placeholder="Enter price" required>
                    </div>

                    <!-- Plan Code Field -->
                    <div class="mb-3">
                        <label for="plan_code" class="form-label font-weight-bold">Plan Code</label>
                        <input type="text" name="plan_code" id="plan_code" class="form-control" placeholder="Enter plan code" required>
                    </div>

                    <!-- Centered Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-100">Create Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

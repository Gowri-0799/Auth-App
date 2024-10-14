<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        
        .wrapper {
            display: flex;
            flex: 1;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            padding-top: 60px;
        }

        .sidebar a {
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }

        .navbar {
            position: fixed;
            width: 100%;
            z-index: 1000;
        }

        .navbar-expand-lg {
            background-color: #343a40;
        }
    </style>
</head>
<body>
<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">My App</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Wrapper -->
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <h5 class="text-center py-3">Menu</h5>
        <a href="{{ route('plans') }}">Plans</a>
        <a href="{{ route('tokens') }}">Access token</a>
        <a href="{{ route('customers') }}">Customers</a>
        <a href="{{ route('subdata') }}">Subscription</a>
        <a href="{{ route('invdata') }}">Invoice</a>
        
    </div>

    <!-- Main Content -->
    <div class="content">
        @yield('content')
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

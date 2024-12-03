<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="icon" href="/assets/images/favicon-32x32.png" type="image/x-icon">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<!-- Chosen CSS (from CDN) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<!-- jQuery (required for Chosen and other functionality) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Chosen JS (from CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&amp;display=swap" rel="stylesheet">
    <link href="/assets/css/plan.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <script async="" src="https://www.clarity.ms/s/0.7.49/clarity.js"></script><script async="" src="https://www.clarity.ms/tag/n8x5ekx79q"></script><script type="text/javascript">
        (function(c, l, a, r, i, t, y) {
            c[a] = c[a] || function() {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i;
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "n8x5ekx79q");
    </script>

    <style>
        .sidebar-item .submenu.show {
            display: block !important;
        }

        /* Apply Bootstrap's primary button color to active sidebar links */
        .sidebar-item .sidebar-link {
           
            padding: 8px 16px; /* Padding for better spacing */
            border-radius: 50px; /* Rounded corners */
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease; /* Smooth transition */
        }

        /* Active link style */
        .sidebar-item .sidebar-link.active {
            background-color: #0d6efd; /* Bootstrap's primary button color */
            color: white !important; /* White text for the active link */
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.5); /* Subtle shadow for depth */
        }

        /* Hover effect for sidebar links */
        .sidebar-item .sidebar-link:hover {
            background-color: #0b5ed7; /* Slightly darker blue on hover */
            color: white !important; /* Keep text white */
            box-shadow: 0 0 12px rgba(0, 123, 255, 0.7); /* Shadow effect on hover */
        }

        .sidebar-logo img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .sidebar-logo {
            padding: 10px 0; /* Add padding to the top and bottom */
            border-bottom: 1px solid #ddd; /* Optional: add a separator line */
        }

        .credit-card {
            background-image: url('../assets/images/card.jpeg');
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .badge {
            padding: 0.4em 0.7em;
            font-size: 0.85rem;
        }

        .badge-success {
            background-color: #E1FFDC;
            color: #159300;
            padding: 1px 13px;
            border-radius: 6px;
            font-weight: 700;
        }

        .badge-fail {
            background-color: #FFE7EC;
            color: #D52B4D;
            padding: 1px 13px;
            border-radius: 6px;
            font-weight: 700;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .text-primary {
            --bs-text-opacity: 1;
            color: rgba(var(--bs-primary-rgb), var(--bs-text-opacity)) !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .text-decoration-underline {
            text-decoration: underline !important;
        }

        .btn-group-sm>.btn, .btn-sm {
            --bs-btn-padding-y: 0.25rem;
            --bs-btn-padding-x: 0.5rem;
            --bs-btn-font-size: 0.875rem;
            --bs-btn-border-radius: var(--bs-border-radius-sm);
        }

        .p-2 {
            padding: .5rem !important;
        }
        .chosen-container .chosen-choices li.search-choice {
    color: black !important; 
}

.chosen-container .chosen-choices input {
    color: black !important;
}

    </style>

</head>

<body>
    <div class="scrollable blurred-bg">
        <!-- Sidebar -->
        <div class="wrapper">
            <aside id="sidebar" class="expand">
                <div class="sidebar-logo text-center py-3">
                    <img src="{{ asset('assets/images/Ln_logo.png') }}" alt="Testlink Logo" class="img-fluid" style="width: 50%; height: 80%;">
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="{{ route('plandb') }}" class="sidebar-link">
                            <span>- Plans </span>
                        </a>
                    </li>
                   
                    <li class="sidebar-item">
                        <a href="{{ route('customerdb') }}" class="sidebar-link">
                            <span>- Partner</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('subdata') }}" class="sidebar-link">
                            <span>- Subscriptions</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('invdata') }}" class="sidebar-link">
                            <span>- Invoice</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('Support.Ticket') }}" class="sidebar-link">
                            <span>- Support Tickets</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('terms.log') }}" class="sidebar-link">
                            <span>- Terms Log</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <!-- Plan Management Link -->
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#planManagement" aria-expanded="true" aria-controls="planManagement">
                            <span>Settings</span>
                            <span class="ms-1">
                                <i class="fa-solid fa-angle-down"></i>
                            </span>
                        </a>

                        <ul id="planManagement" class="ms-5 sidebar-dropdown list-unstyled collapse show" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="{{ route('plandb') }}" class="sidebar-link">
                                - Plans
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('affiliates.index') }}" class="sidebar-link">
                                - Affiliate
                                </a>
                            </li>
                            <li class="sidebar-item"> 
                                <a href="{{ route('admin.index') }}" class="sidebar-link">
                                - Admins
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('admin.profile') }}" class="sidebar-link">
                                - Profile
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                </ul>
                
                <div class="bottom-footer mt-auto">
                    <hr class="line mt-0">
                    <div>
                        <a class="sidebar-footer p-0 m-0 mt-3 mb-2 ms-4">
                            <span class="text-dark fw-bold"><strong>Welcome!</strong></span>
                        </a>
                        <a class="sidebar-footer p-0 m-0 mb-2 ms-4">
                            <span class="text-dark">{{ session('user_email') }}</span>
                        </a>
                        <a href="{{ route('adminlogin') }}" class="sidebar-footer text-center p-0 m-0 mb-4 ms-4 logout">
                            <span class="btn fw-bold text-primary">Logout</span>
                        </a>
                    </div>

                    <div class="footer p-0 m-0 mt-3">
                        <a href="#" class="sidebar-footer footer">
                            <p class="text-dark small text-wrap p-0 mb-4">
                                <span class="text-dark p-0 mb-4">@Testlink Technologies 2024</span>
                            </p>
                        </a>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    
    <!-- JavaScript to handle submenu -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Highlight active sidebar links
    const sidebarLinks = document.querySelectorAll('.sidebar-link'); 

    function removeActiveClass() {
        sidebarLinks.forEach(function(link) {
            link.classList.remove('active');
        });
    }

    sidebarLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            removeActiveClass();
            this.classList.add('active');
        });
    });
});
    </script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/js/plan.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
</body>

</html>
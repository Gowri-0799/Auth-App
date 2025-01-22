<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" href="/assets/images/favicon-32x32.png" type="image/x-icon">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&amp;display=swap" rel="stylesheet">
    <link href="/assets/css/plan.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Sidebar Styles */
        #sidebar {
            position: fixed;
            top: 0; /* Aligns the sidebar with the top of the viewport */
            left: 0;
            height: 100%;
            width: 250px;
            background: #f8f9fa;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            z-index: 1040;
            overflow-y: auto;
            padding: 0; /* Removes any padding inside the sidebar */
            margin: 0; /* Ensures no margin offsets the sidebar */
        }
        
        #sidebar.show {
            transform: translateX(0);
        }

        .content {
            transition: margin-left 0.3s ease-in-out;
        }

        #sidebar.show ~ .content {
            margin-left: 250px;
        }

        @media (min-width: 992px) {
    #sidebar {
        transform: translateX(0); /* Sidebar is always visible */
    }

    .content {
        margin-left: 250px; /* Adjust content margin to account for the sidebar */
        display: block; /* Ensure content is always visible */
    }
    #closeSidebar {
        display: block; /* Show on mobile view */
    }
}

@media (max-width: 991.98px) {
    #sidebar {
        transform: translateX(-100%);
        z-index: 1050; /* Ensure it's above other elements */
    }

    #sidebar.show {
        transform: translateX(0);
    }

    .content {
        display: block; /* Default content visibility */
    }
    #closeSidebar {
        display: block; /* Show on mobile view */
    }
}

        .wrapper {
            display: flex;
            position: relative;
            z-index: 1;
            overflow: visible;
        }

        /* Sidebar Menu Styling */
        .sidebar-nav {
            display: block;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-item {
            margin: 0;
            display: list-item;
        }

        .sidebar-link {
            display: block;
            padding: 10px 20px;
            font-size: 16px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar-link:hover {
            background-color: rgba(0, 0, 0, 0.1);
            color: #0d6efd;
        }

        .sidebar-dropdown {
            margin-left: 20px;
            list-style: none;
        }

        .sidebar-dropdown .sidebar-item {
            margin-top: 10px;
        }
    </style>

</head>

<body>
    <!-- Navbar for Mobile View -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light d-lg-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#"> </a>
        </div>
    </nav>

    <div class="scrollable blurred-bg">
        <!-- Sidebar -->
            <aside id="sidebar">
            <button id="closeSidebar" class="btn-close d-lg-none" aria-label="Close" style="position: absolute; top: 20px; right: 20px;"></button>

                <div class="sidebar-logo text-center py-3">
                    <img src="{{ asset('assets/images/Ln_logo.png') }}" alt="Testlink Logo" class="img-fluid" style="width: 70%; height: 100%;">
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="{{ route('customer.provider') }}" class="sidebar-link">Provider Data</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('customer.companyinfo') }}" class="sidebar-link">
                            <span>Company Info</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('usagereports') }}" class="sidebar-link">
                            <span>Usage Reports</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('customer.details') }}" class="sidebar-link">
                            <span>Profile</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <!-- Plan Management Link -->
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#planManagement" aria-expanded="true" aria-controls="planManagement">
                            <span>Plan Management</span>
                            <span class="ms-1">
                                <i class="fa-solid fa-angle-down"></i>
                            </span>
                        </a>

                        <!-- Submenu for Plan Management (show by default) -->
                        <ul id="planManagement" class="ms-5 sidebar-dropdown list-unstyled collapse show">
                            <li class="sidebar-item">
                                <a href="{{ route('showplan') }}" class="sidebar-link">
                                    - Plan Options
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('customer.subscriptions') }}" class="sidebar-link">
                                    - Plan Subscriptions
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('customer.invoices') }}" class="sidebar-link">
                                    - Invoices
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('customer.credites') }}" class="sidebar-link">
                                    - Credit Notes
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="{{ route('customer.support') }}" class="sidebar-link">
                                    - Support Ticket
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <div class="bottom-footer">
                    <hr class="line mt-0">
                    <div>
                        <a class="sidebar-footer p-0 m-0 mt-3 mb-2 ms-4">
                            <span class="text-dark fw-bold"><strong>Welcome!</strong></span>
                        </a>
                        <a class="sidebar-footer p-0 m-0 mb-2 ms-4">
                            <span class="text-dark">{{ session('user_email') }}</span>
                        </a>
                        <a href="/logout" class="sidebar-footer text-center p-0 m-0 mb-4 ms-4 logout">
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

        <!-- Content Section -->
        <div class="content p-4" style="flex-grow: 1;">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Sidebar Toggle Script -->
    <script>
 document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');
    const toggleButton = document.getElementById('sidebarToggle');
    const closeButton = document.getElementById('closeSidebar');
    const sidebarLinks = document.querySelectorAll('.sidebar-link');

    // Check if closeButton exists
    if (closeButton) {
        console.log('Close button is available');
    }

    function isMobileView() {
        return window.innerWidth <= 991.98; // Match the breakpoint in your CSS
    }

    toggleButton.addEventListener('click', function () {
        if (isMobileView()) {
            sidebar.classList.toggle('show');
            content.style.display = sidebar.classList.contains('show') ? 'none' : 'block';
        }
    });

    // Close sidebar when close button is clicked (mobile view only)
    if (closeButton) {
        closeButton.addEventListener('click', function () {
            if (isMobileView()) {
                sidebar.classList.remove('show');
                content.style.display = 'block';
            }
        });
    }

    // Hide sidebar and show content when a sidebar link is clicked in mobile view
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (isMobileView()) {
                sidebar.classList.remove('show');
                content.style.display = 'block';
            }
        });
    });

    // Ensure content is always shown on larger screens
    window.addEventListener('resize', function () {
        if (!isMobileView()) {
            sidebar.classList.add('show'); // Keep sidebar visible
            content.style.display = 'block'; // Ensure content is visible
        } else if (!sidebar.classList.contains('show')) {
            content.style.display = 'block'; // Show content when resizing back to mobile
        }
    });
});


    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="/assets/js/plan.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
</body>

</html>
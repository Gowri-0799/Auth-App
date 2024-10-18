<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/plan.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        .sidebar-item .submenu.show {
            display: block !important;
        }

        /* Apply Bootstrap's primary button color to active sidebar links */
        .sidebar-item .sidebar-link {
            color: #6c757d; /* Default color for inactive links */
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
            badge-success {
    background-color: #E1FFDC;
    color: #159300;
    padding: 1px 13px 1px 13px;
    padding-top: 1px;
    padding-right: 13px;
    padding-bottom: 1px;
    padding-left: 13px;
    border-radius: 6px;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
    border-bottom-left-radius: 6px;
    font-weight: 700;
}
        }

        .badge-fail {
    background-color: #FFE7EC;
    color: #D52B4D;
    padding: 1px 13px 1px 13px;
    padding-top: 1px;
    padding-right: 13px;
    padding-bottom: 1px;
    padding-left: 13px;
    border-radius: 6px;
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
    border-bottom-left-radius: 6px;
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
    text-decoration-line: underline !important;
    text-decoration-thickness: initial !important;
    text-decoration-style: initial !important;
    text-decoration-color: initial !important;
}
.btn-group-sm>.btn, .btn-sm {
    --bs-btn-padding-y: 0.25rem;
    --bs-btn-padding-x: 0.5rem;
    --bs-btn-font-size: 0.875rem;
    --bs-btn-border-radius: var(--bs-border-radius-sm);
}
.p-2 {
    padding: .5rem !important;
    padding-top: 0.5rem !important;
    padding-right: 0.5rem !important;
    padding-bottom: 0.5rem !important;
    padding-left: 0.5rem !important;
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
                        <a href="#" class="sidebar-link">
                            <span>Provider Data </span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>Company Info</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <span>Usage Reports</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href=" {{ route('customer.details') }}" class="sidebar-link">
                            <span>Profile</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
    <!-- Plan Management Link -->
    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#planManagement" aria-expanded="false" aria-controls="planManagement">
        <span>Plan Management</span>
        <span class="ms-1">
            <i class="fa-solid fa-angle-down"></i>
        </span>
    </a>

    <!-- Submenu for Plan Management -->
    <ul id="planManagement" class="ms-5 sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
        <!-- Each item has your custom routes -->
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
            <a href="{{route('customer.support')}}" class="sidebar-link">
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
                            <span class="btn fw-bold text-primary ">Logout</span>
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
    // Handle the collapse for the "Plan Management" toggle
    var planManagementToggle = document.querySelector('[data-bs-target="#planManagement"]');
    
    planManagementToggle.addEventListener('click', function(e) {
        var submenu = document.querySelector('#planManagement');
        if (submenu.classList.contains('show')) {
            submenu.classList.remove('show');
        } else {
            submenu.classList.add('show');
        }
        e.preventDefault();
    });
});
            // Ensure submenu items don't close the submenu when clicked
            const submenuLinks = document.querySelectorAll('#plan-management-submenu .sidebar-link');
            submenuLinks.forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.stopPropagation(); // Prevent submenu from closing
                });
            });

            // Highlight active sidebar links
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            function removeActiveClass() {
                sidebarLinks.forEach(function (link) {
                    link.classList.remove('active');
                });
            }

            sidebarLinks.forEach(function (link) {
                link.addEventListener('click', function (e) {
                    removeActiveClass();
                    this.classList.add('active');
                });
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="/assets/js/plan.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
</body>

</html>

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

    

<body>
    <div class="scrollable blurred-bg">
        <!-- Sidebar -->
        <div class="wrapper">
            <aside id="sidebar" class="expand">
                <div class="sidebar-logo text-center py-3">
                    <img src="{{ asset('assets/images/Ln_logo.png') }}" alt="Testlink Logo" class="img-fluid" style="width: 70%; height: 100%;">
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="{{ route('customer.provider') }}" class="sidebar-link">
                            <span>Provider Data</span>
                        </a>
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
                        <ul id="planManagement" class="ms-5 sidebar-dropdown list-unstyled collapse show" data-bs-parent="#sidebar">
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
    const sidebarLinks = document.querySelectorAll('.sidebar-link'); // Use dot for class selector

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="/assets/js/plan.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
</body>

</html>
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
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    <link href="/assets/css/plan.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&amp;display=swap" rel="stylesheet">

    <!-- Load jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Chosen JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>

    <script async="" src="https://www.clarity.ms/tag/n8x5ekx79q"></script>
    <script type="text/javascript">
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

        /* Sidebar and toggle button styles */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background: #f8f9fa;
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            z-index: 1040;
            overflow-y: auto;
            padding: 0;
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
                transform: translateX(0);
            }

            .content {
                margin-left: 250px;
                display: block;
            }
            #closeSidebar {
                position: absolute;
        top: 20px;
        right: 20px;
        display: block; /* Show on mobile view */
    }
        }

        @media (max-width: 991.98px) {
            #sidebar {
                transform: translateX(-100%);
                z-index: 1050;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            .content {
                display: block;
            }
            #closeSidebar {
                position: absolute;
        top: 20px;
        right: 20px;
        display: block; /* Show on mobile view */
    }
        }

        .sidebar-logo img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
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
        <div class="wrapper">
            <aside id="sidebar">  
            <button id="closeSidebar" class="btn-close d-lg-none" aria-label="Close" style="position: absolute; top: 20px; right: 20px;"></button>

                <div class="sidebar-logo text-center py-3">
                    <img src="{{ asset('assets/images/Ln_logo.png') }}" alt="Testlink Logo" class="img-fluid" style="width: 70%; height: 100%;">
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
                       <!-- Settings Link -->
                           <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" data-bs-target="#settingsMenu" aria-expanded="true" aria-controls="settingsMenu">
                             <span>Settings</span>
                             <span class="ms-1">
                               <i class="fa-solid fa-angle-down"></i>
                              </span>
                           </a>

                         <!-- Submenu for Settings -->
                           <ul id="settingsMenu" class="ms-5 sidebar-dropdown list-unstyled collapse show" data-bs-parent="#sidebar">
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

    <!-- Toggle Sidebar Script -->
    <script>
       document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.querySelector('.content');
    const toggleButton = document.getElementById('sidebarToggle');
    const closeButton = document.getElementById('closeSidebar');

    function isMobileView() {
        return window.innerWidth <= 991.98; // Match the breakpoint in your CSS
    }

    toggleButton.addEventListener('click', function () {
        if (isMobileView()) {
            sidebar.classList.toggle('show');

            if (sidebar.classList.contains('show')) {
                content.style.display = 'none';
            } else {
                content.style.display = 'block';
            }
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

    // Ensure sidebar is always visible in normal view
    window.addEventListener('resize', function () {
        if (!isMobileView()) {
            sidebar.classList.add('show');
            content.style.display = 'block';
        }
    });
});

    </script>
</body>

</html>


      <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/assets/js/plan.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
</body>

</html>
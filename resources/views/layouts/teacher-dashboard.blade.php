{{-- resources/views/layouts/teacher-dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Youth Cybersafe Teacher Dashboard')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=K2D:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('layouts.dashboard.style')
    <style>
    body {
        font-family: 'K2D', sans-serif;
    }
    .logo-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-inline-start: 18px;
    }
    .logo-text {
        font-family: 'K2D', sans-serif !important;
        font-weight: 900 !important;
        text-shadow: 0 0 1px rgba(0,0,0,0.3);
        letter-spacing: 0.5px;
    }
    .sidebar {
        background: #f5f5f5;
        box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        border-right: 1px solid #e0e0e0;
        overflow-y: auto;
    }
    .main-content {
        background: #f5f5f5;
        min-height: 100vh;
    }
    .sidebar .nav-link {
        line-height: 1.3 !important;
        padding: 12px 20px !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        display: flex !important;
        align-items: flex-start !important;
    }
    .sidebar .nav-link i {
        flex-shrink: 0 !important;
        margin-right: 25px !important;
        margin-top: 2px !important;
    }
    
    /* Student List Report Table Styles */
    .student-reports-table {
        border-collapse: collapse;
        border: 2px solid #ddd;
    }
    .student-reports-table th {
        font-weight: 600;
        color: #2d3748;
        border: 1px solid #ddd;
        padding: 12px;
        background-color: #f8f9fa;
        text-align: center;
    }
    .student-reports-table td {
        border: 1px solid #ddd;
        padding: 12px;
        vertical-align: middle;
        text-align: center;
    }
    .student-reports-table tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }
    .student-reports-table tbody tr:nth-child(even) {
        background-color: white;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            width: 100% !important;
            position: relative !important;
            height: auto !important;
        }
        .main-content {
            width: 100% !important;
            margin-left: 0 !important;
        }
    }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="sidebar p-0" style="width: 20%; position: fixed; height: 100vh;">
                <div class="p-3 d-flex flex-column h-100 mt-3">
                    <div class="d-flex align-items-center mb-4">
                        <div class="logo-container">
                            <img src="{{ asset('images/logo.png') }}" alt="Youth Cybersafe Logo" style="width: 70px; height: 70px; object-fit: contain;">
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 logo-text" style="color: #2d3748;">YOUTH</h6>
                            <h6 class="mb-0 logo-text" style="color: #2b2b2b;">CYBERSAFE</h6>
                        </div>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link @if(request()->routeIs('teacher.dashboard')) active @endif" href="{{ route('teacher.dashboard') }}">
                            <i class="fas fa-th-large me-2"></i> Dashboard
                        </a>
                        <a class="nav-link @if(request()->routeIs('teacher.behavioral-report')) active @endif" href="{{ route('teacher.behavioral-report') }}">
                            <i class="fas fa-chart-bar me-2"></i> Behavioral Report
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link w-100 bg-transparent border-0 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i> Sign Out
                            </button>
                        </form>
                    </nav>
                    
                    <div class="sidebar-profile mt-auto">
                        <div class="profile-card">
                            <div><strong>{{ Auth::user()->full_name ?? (Auth::user()->name . ' ' . Auth::user()->lastname) }}</strong></div>
                            <div><small>{{ Auth::user()->role_thai ?? 'คุณครู' }}</small></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-content" style="width: 80%; margin-left: 20%; padding: 30px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>@yield('page-title', 'Dashboard')</h2>
                </div>

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
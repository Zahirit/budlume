<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - CitiCarCanada</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Vite CSS/JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: #111827;
            color: #fff;
            min-height: 100vh;
            padding: 20px 0;
        }

        .sidebar .brand {
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
            color: #fff;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 8px;
            margin: 4px 12px;
            transition: 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #2563eb;
            color: #fff;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            padding: 15px 25px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-area {
            padding: 25px;
        }

        .card-box {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="admin-wrapper">
    
    {{-- Sidebar --}}
    <div class="sidebar">
        <div class="brand">🚗 CitiCarCanada</div>

        <nav class="nav flex-column">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-car-front"></i> Vehicle Management
            </a>
            
            <a href="{{ route('admin.categories.index') }}" class="nav-link">
                <i class="bi bi-tags"></i> Categories
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-diagram-3"></i> Model
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-grid"></i> Body Type
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-fuel-pump"></i> Fuel Type
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-gear-wide-connected"></i> Transmission
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-people"></i> Customers
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-envelope"></i> Contact Messages
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-gear"></i> Settings
            </a>
        </nav>
    </div>

    {{-- Main Content --}}
    <div class="main-content">

        {{-- Topbar --}}
    <div class="topbar">
        <h4 class="mb-0">{{ $title ?? 'Dashboard' }}</h4>

        <div class="d-flex align-items-center gap-3">
            <span class="fw-semibold">
                Welcome, {{ auth()->user()->name ?? 'Admin' }}
            </span>

            <a href="#" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>

        {{-- Content --}}
        <div class="content-area">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
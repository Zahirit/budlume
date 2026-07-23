@php
    $setting = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>{{ $title ?? 'Admin Panel' }} - Budlume</title>

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

        <div class="brand">

    <span>{{ $setting->site_name ?? 'Budlume' }}</span>
    </div>

        <nav class="nav flex-column">

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                Categories
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                Products
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart"></i> Orders
            </a>

          <a href="{{ route('admin.customers.index') }}"
               class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                Customers
            </a>

           <a href="{{ route('admin.contact-messages.index') }}"
                class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i>
                    <span>Contact Messages</span>
                </a>


                <a href="{{ route('admin.delivery-men.index') }}"
                   class="{{ request()->routeIs('admin.delivery-men.*') ? 'active' : '' }}">
                    🚚 Delivery Partner
                </a>

           <a href="{{ route('admin.settings.index') }}"
               class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                Settings
            </a>

        </nav>
    </div>

    {{-- Main Content --}}
    <div class="main-content">

        {{-- Topbar --}}
        <div class="topbar">

            <h4 class="mb-0">
                {{ $title ?? 'Dashboard' }}
            </h4>

            <div class="d-flex align-items-center gap-3">

                <span class="fw-semibold">
                    Welcome, {{ auth()->user()->name ?? 'Admin' }}
                </span>

               <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf

                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>

            </div>
        </div>

        {{-- Content --}}
        <div class="content-area">
            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const chartCanvas = document.getElementById('salesChart');

if(chartCanvas){

new Chart(chartCanvas,{

type:'line',

data:{

labels:[
'Jan','Feb','Mar','Apr','May','Jun',
'Jul','Aug','Sep','Oct','Nov','Dec'
],

datasets:[{

label:'Sales',

data:[12,19,15,22,28,24,35,30,40,38,45,50],

borderColor:'#22c55e',

backgroundColor:'rgba(34,197,94,.15)',

fill:true,

tension:.4,

pointRadius:5

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{
display:false
}

},

scales:{

y:{
beginAtZero:true
}

}

}

});

}

</script>

@stack('scripts')

</body>
</html>
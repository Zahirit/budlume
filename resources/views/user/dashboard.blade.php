@extends('frontend.layouts.app')

@section('title', 'My Account | Budlume')

@section('robots', 'noindex, nofollow')

@section('content')

<section class="customer-dashboard">

    <div class="customer-dashboard-container">

        {{-- =========================
             PAGE HEADER
        ========================== --}}
        <div class="customer-dashboard-header">

            <span class="dashboard-small-title">
                MY ACCOUNT
            </span>

            <h1>
                Welcome, {{ Auth::user()->name }}
            </h1>

            <p>
                Manage your account, view your orders and keep track
                of your purchases.
            </p>

        </div>


        <div class="customer-dashboard-layout">

            {{-- =========================
                 LEFT SIDEBAR
            ========================== --}}
            <aside class="customer-sidebar">

                {{-- PROFILE --}}
                <div class="customer-profile-box">

                    <div class="customer-avatar">

                        @if(Auth::user()->profile_photo)

                            <img
                                src="{{ asset('uploads/profile/' . Auth::user()->profile_photo) }}"
                                alt="{{ Auth::user()->name }}"
                            >

                        @else

                            <div class="customer-avatar-placeholder">

                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                            </div>

                        @endif

                    </div>

                    <h3>
                        {{ Auth::user()->name }}
                    </h3>

                    <span class="customer-role">
                        {{ strtoupper(Auth::user()->role ?? 'customer') }}
                    </span>

                    <p>
                        {{ Auth::user()->email }}
                    </p>

                </div>


                {{-- SIDEBAR MENU --}}
                <nav class="customer-dashboard-menu">

                    <a href="{{ route('account.dashboard') }}"
                       class="active">

                        <i class="bi bi-grid"></i>

                        <span>Dashboard</span>

                    </a>


                    <a href="{{ route('orders.index') }}">

                        <i class="bi bi-bag"></i>

                        <span>My Orders</span>

                    </a>


                    <a href="{{ route('profile.edit') }}">

                        <i class="bi bi-person"></i>

                        <span>Account Details</span>

                    </a>


                    {{-- CHANGE PASSWORD --}}

                        <a href="{{ route('account.password') }}">

                        <i class="bi bi-lock"></i>

                        <span>Change Password</span>

                    </a>


                    <a href="{{ route('shop') }}">

                        <i class="bi bi-shop"></i>

                        <span>Continue Shopping</span>

                    </a>


                    <form method="POST"
                          action="{{ route('logout') }}">

                        @csrf

                        <button type="submit">

                            <i class="bi bi-box-arrow-right"></i>

                            <span>Logout</span>

                        </button>

                    </form>

                </nav>

            </aside>


            {{-- =========================
                 DASHBOARD CONTENT
            ========================== --}}
            <div class="customer-dashboard-content">


                {{-- =========================
                     6 STATISTIC CARDS
                ========================== --}}
                <div class="customer-stat-grid customer-stat-grid-six">


                    {{-- 1. TOTAL ORDERS --}}
                    <div class="customer-stat-card stat-card-green">

                        <div class="stat-icon">

                            <i class="bi bi-bag-check"></i>

                        </div>

                        <div class="stat-info">

                            <span>
                                Total Orders
                            </span>

                            <h2>
                                {{ $totalOrders }}
                            </h2>

                        </div>

                        <div class="stat-wave"></div>

                    </div>


                    {{-- 2. PENDING ORDERS --}}
                    <div class="customer-stat-card stat-card-orange">

                        <div class="stat-icon">

                            <i class="bi bi-clock-history"></i>

                        </div>

                        <div class="stat-info">

                            <span>
                                Pending Orders
                            </span>

                            <h2>
                                {{ $pendingOrders }}
                            </h2>

                        </div>

                        <div class="stat-wave"></div>

                    </div>


                    {{-- 3. COMPLETED ORDERS --}}
                    <div class="customer-stat-card stat-card-green">

                        <div class="stat-icon">

                            <i class="bi bi-check-circle"></i>

                        </div>

                        <div class="stat-info">

                            <span>
                                Completed Orders
                            </span>

                            <h2>
                                {{ $completedOrders }}
                            </h2>

                        </div>

                        <div class="stat-wave"></div>

                    </div>


                    {{-- 4. TOTAL PRODUCTS --}}
                    
                    <div class="customer-stat-card stat-card-purple">

                        <div class="stat-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <div class="stat-info">
                            <span>Total Products</span>

                            <h2>{{ $totalProducts }}</h2>
                        </div>

                        <div class="stat-wave"></div>

                    </div>


                    {{-- 5. TOTAL SPENT --}}
                    <div class="customer-stat-card stat-card-blue">

                        <div class="stat-icon">

                            <i class="bi bi-wallet2"></i>

                        </div>

                        <div class="stat-info">

                            <span>
                                Total Spent
                            </span>

                            <h2>
                                ${{ number_format($totalSpent, 2) }}
                            </h2>

                        </div>

                        <div class="stat-wave"></div>

                    </div>


                    {{-- 6. TOTAL SAVINGS --}}
                    <div class="customer-stat-card stat-card-red">

                        <div class="stat-icon">

                            <i class="bi bi-currency-dollar"></i>

                        </div>

                        <div class="stat-info">

                            <span>
                                Total Savings
                            </span>

                            <h2>
                                ${{ number_format($totalSavings ?? 0, 2) }}
                            </h2>

                        </div>

                        <div class="stat-wave"></div>

                    </div>

                </div>


                {{-- =========================
                     RECENT ORDERS
                ========================== --}}
                <div class="customer-orders-box">

                    <div class="customer-orders-heading">

                        <div>

                            <span class="dashboard-small-title">
                                ORDER HISTORY
                            </span>

                            <h2>
                                Recent Orders
                            </h2>

                        </div>


                        <a href="{{ route('orders.index') }}"
                           class="view-orders-btn">

                            View All Orders

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>


                    {{-- =========================
                         ORDERS EXIST
                    ========================== --}}
                    @if($recentOrders->count() > 0)

                        <div class="customer-orders-table-wrap">

                            <table class="customer-orders-table">

                                <thead>

                                    <tr>

                                        <th>Order</th>

                                        <th>Date</th>

                                        <th>Status</th>

                                        <th>Total</th>

                                        <th>Action</th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($recentOrders as $order)

                                        <tr>

                                            {{-- ORDER NUMBER --}}
                                            <td>

                                                <strong>

                                                    #{{ $order->order_number }}

                                                </strong>

                                            </td>


                                            {{-- DATE --}}
                                            <td>

                                                {{ $order->created_at->format('M d, Y') }}

                                            </td>


                                            {{-- STATUS --}}
                                            <td>

                                                <span
                                                    class="order-status status-{{ strtolower($order->status) }}">

                                                    {{ ucfirst($order->status) }}

                                                </span>

                                            </td>


                                            {{-- TOTAL --}}
                                            <td>

                                                <strong>

                                                    ${{ number_format($order->total_amount, 2) }}

                                                </strong>

                                            </td>


                                            {{-- ACTION --}}
                                            <td>

                                                <a
                                                    href="{{ route('orders.show', $order) }}"
                                                    class="order-view-link">

                                                    View

                                                    <i class="bi bi-arrow-right"></i>

                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                    @else


                        {{-- =========================
                             EMPTY ORDER STATE
                        ========================== --}}
                        <div class="customer-no-orders">

                            <div class="no-orders-icon">

                                <i class="bi bi-bag"></i>

                            </div>


                            <h3>
                                No orders yet
                            </h3>


                            <p>

                                You haven't placed any orders yet.

                                <br>

                                Start exploring our products.

                            </p>


                            <a href="{{ route('shop') }}"
                               class="customer-shop-btn">

                                START SHOPPING

                                <i class="bi bi-arrow-right"></i>

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
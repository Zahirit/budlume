<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Delivery Dashboard | Budlume</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
        }

        .topbar {
            background: #111827;
            color: #fff;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-size: 22px;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .logout-btn {
            border: 0;
            background: #dc3545;
            color: #fff;
            padding: 9px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .dashboard {
            max-width: 1250px;
            margin: 0 auto;
            padding: 35px 20px;
        }

        .welcome-card {
            background: #fff;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0,0,0,.06);
        }

        .welcome-card h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .welcome-card p {
            margin: 0;
            color: #666;
        }

        .status-row {
            margin-top: 18px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .badge-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-available {
            background: #dcfce7;
            color: #166534;
        }

        .badge-unavailable {
            background: #e5e7eb;
            color: #374151;
        }

        .stats {
            display: grid;
            grid-template-columns:
                repeat(4, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #fff;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,.06);
        }

        .stat-title {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 30px;
            font-weight: 700;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .panel {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,.06);
        }

        .panel-header {
            padding: 18px 20px;
            border-bottom: 1px solid #eee;
            font-weight: 700;
        }

        .panel-body {
            padding: 20px;
        }

        .empty-state {
            text-align: center;
            padding: 45px 15px;
            color: #777;
        }

        .profile-row {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .profile-row:last-child {
            border-bottom: 0;
        }

        .profile-label {
            display: block;
            font-size: 12px;
            color: #777;
            margin-bottom: 5px;
        }

        .profile-value {
            font-weight: 600;
        }

        @media (max-width: 900px) {

            .stats {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 550px) {

            .topbar {
                padding: 14px 15px;
            }

            .stats {
                grid-template-columns: 1fr;
            }
        }

        .badge-online {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-offline {
            background: #fee2e2;
            color: #991b1b;
        }

        .location-message {
            margin-top: 12px;
            font-size: 13px;
            color: #6b7280;
        }

        /* Incoming Delivery Offer */

.delivery-offer {
    background: #fff7ed;
    border: 2px solid #f97316;
    border-radius: 12px;
    padding: 22px;
    margin-bottom: 25px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, .08);
}

.delivery-offer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 18px;
}

.delivery-offer-title {
    margin: 0;
    font-size: 22px;
}

.offer-countdown {
    background: #dc2626;
    color: #fff;
    padding: 9px 14px;
    border-radius: 20px;
    font-weight: 700;
    white-space: nowrap;
}

.offer-details {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.offer-detail {
    background: #fff;
    border: 1px solid #fed7aa;
    border-radius: 8px;
    padding: 14px;
}

.offer-detail-label {
    display: block;
    color: #777;
    font-size: 12px;
    margin-bottom: 5px;
}

.offer-detail-value {
    font-weight: 700;
}

.offer-actions {
    display: flex;
    gap: 12px;
}

.offer-btn {
    border: 0;
    border-radius: 7px;
    padding: 11px 22px;
    font-weight: 700;
    cursor: pointer;
}

.offer-accept {
    background: #16a34a;
    color: #fff;
}

.offer-reject {
    background: #dc2626;
    color: #fff;
}

@media (max-width: 700px) {
    .delivery-offer-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .offer-details {
        grid-template-columns: 1fr;
    }
}
    </style>

</head>

<body>

<header class="topbar">

    <div class="brand">
        🚚 Budlume Delivery
    </div>

    <div class="topbar-right">

        <span>
            Welcome,
            <strong>{{ $user->name }}</strong>
        </span>

        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <button
                type="submit"
                class="logout-btn"
            >
                Logout
            </button>

        </form>

    </div>

</header>


<main class="dashboard">

    {{-- Welcome --}}
    <section class="welcome-card">

        <h1>
            Delivery Partner Dashboard
        </h1>

        <p>
            Welcome back, {{ $user->name }}.
            Manage your deliveries and delivery activity here.
        </p>

         <div class="status-row">

            <span class="badge badge-approved">
                ✓ Approved Partner
            </span>

            @if($deliveryProfile->is_available)

                <span class="badge badge-available">
                    ● Available
                </span>

            @else

                <span class="badge badge-unavailable">
                    ● Not Available
                </span>

            @endif

            <span
                id="online-status"
                class="badge {{ $deliveryProfile->is_online ? 'badge-online' : 'badge-offline' }}"
            >
                {{ $deliveryProfile->is_online ? '● Online' : '● Connecting GPS...' }}
            </span>

        </div>

        <div id="location-message" class="location-message">
            Waiting for location permission...
        </div>

    </section>

    {{-- Incoming Delivery Offer --}}

@if($activeOffer)

    <section
        class="delivery-offer"
        id="delivery-offer"
        data-expires-at="{{ $activeOffer->expires_at->toIso8601String() }}"
    >

        <div class="delivery-offer-header">

            <div>
                <h2 class="delivery-offer-title">
                    🚚 New Delivery Request
                </h2>

                <small>
                    Please accept or reject this delivery before time expires.
                </small>
            </div>

            <div
                class="offer-countdown"
                id="offer-countdown"
            >
                30s
            </div>

        </div>

        <div class="offer-details">

            <div class="offer-detail">
                <span class="offer-detail-label">
                    Order Number
                </span>

                <span class="offer-detail-value">
                    {{ $activeOffer->order->order_number ?? 'N/A' }}
                </span>
            </div>

            <div class="offer-detail">
                <span class="offer-detail-label">
                    Distance
                </span>

                <span class="offer-detail-value">
                    {{ number_format((float) $activeOffer->distance_km, 2) }} km
                </span>
            </div>

            <div class="offer-detail">
                <span class="offer-detail-label">
                    Delivery Area
                </span>

                <span class="offer-detail-value">
                    {{ $activeOffer->order->delivery_city ?? 'N/A' }}
                </span>
            </div>

        </div>

        <div class="offer-actions">

            <form
                method="POST"
                action="{{ route('delivery.offers.accept', $activeOffer) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="offer-btn offer-accept"
                >
                    ✓ Accept Delivery
                </button>
            </form>

            <form
                method="POST"
                action="{{ route('delivery.offers.reject', $activeOffer) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="offer-btn offer-reject"
                >
                    ✕ Reject
                </button>
            </form>

        </div>

    </section>

@endif


    {{-- Statistics --}}
    <section class="stats">

        <div class="stat-card">

            <div class="stat-title">
                Assigned Orders
            </div>

            <div class="stat-number">
                0
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Active Deliveries
            </div>

            <div class="stat-number">
                0
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Completed Deliveries
            </div>

            <div class="stat-number">
                0
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-title">
                Customer Reviews
            </div>

            <div class="stat-number">
                0
            </div>

        </div>

    </section>


    <section class="content-grid">

        {{-- Orders --}}
        <div class="panel">

            <div class="panel-header">
                Current Delivery Assignments
            </div>

            <div class="panel-body">

                <div class="empty-state">

                    <div style="font-size:45px; margin-bottom:15px;">
                        📦
                    </div>

                    <strong>
                        No deliveries assigned yet.
                    </strong>

                    <p>
                        New delivery assignments will appear here.
                    </p>

                </div>

            </div>

        </div>


        {{-- Profile --}}
        <div class="panel">

            <div class="panel-header">
                Delivery Partner Profile
            </div>

            <div class="panel-body">

                <div class="profile-row">

                    <span class="profile-label">
                        Name
                    </span>

                    <span class="profile-value">
                        {{ $user->name }}
                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Mobile
                    </span>

                    <span class="profile-value">
                        {{ $user->phone ?? 'N/A' }}
                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Driving License
                    </span>

                    <span class="profile-value">

                        {{
                            $deliveryProfile
                                ->driving_license_number
                            ?? 'N/A'
                        }}

                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Account Status
                    </span>

                    <span class="profile-value">
                        Approved
                    </span>

                </div>


                <div class="profile-row">

                    <span class="profile-label">
                        Availability
                    </span>

                    <span class="profile-value">

                        {{
                            $deliveryProfile->is_available
                                ? 'Available'
                                : 'Not Available'
                        }}

                    </span>

                </div>

            </div>

        </div>

    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const statusBadge =
        document.getElementById('online-status');

    const locationMessage =
        document.getElementById('location-message');

    let lastSentAt = 0;

    /*
    |--------------------------------------------------------------------------
    | Change GPS Status Display
    |--------------------------------------------------------------------------
    */
    function setStatus(text, online = false) {

        if (!statusBadge) {
            return;
        }

        statusBadge.textContent = text;

        statusBadge.classList.remove(
            'badge-online',
            'badge-offline'
        );

        statusBadge.classList.add(
            online
                ? 'badge-online'
                : 'badge-offline'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Send GPS Location To Laravel
    |--------------------------------------------------------------------------
    */
    function sendLocation(latitude, longitude) {

        fetch(
            '{{ route('delivery.location.update') }}',
            {
                method: 'POST',

                headers: {
                    'Content-Type':
                        'application/json',

                    'Accept':
                        'application/json',

                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}'
                },

                body: JSON.stringify({
                    latitude: latitude,
                    longitude: longitude
                })
            }
        )

        .then(async response => {

            const data =
                await response.json();

            if (!response.ok) {

                throw new Error(
                    data.message ||
                    'Location update failed.'
                );
            }

            return data;
        })

        .then(data => {

            setStatus(
                '● Online - GPS Active',
                true
            );

            if (locationMessage) {

                locationMessage.textContent =
                    'Live location connected successfully.';
            }

            console.log(
                'Budlume GPS updated:',
                latitude,
                longitude
            );
        })

        .catch(error => {

            console.error(
                'Location update error:',
                error
            );

            setStatus(
                '● GPS Update Failed',
                false
            );

            if (locationMessage) {

                locationMessage.textContent =
                    error.message;
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Start Browser GPS Tracking
    |--------------------------------------------------------------------------
    */
    function startLocationTracking() {

        if (!navigator.geolocation) {

            setStatus(
                '● GPS Not Supported',
                false
            );

            if (locationMessage) {

                locationMessage.textContent =
                    'Geolocation is not supported by this browser.';
            }

            return;
        }


        if (locationMessage) {

            locationMessage.textContent =
                'Requesting location permission...';
        }


        navigator.geolocation.watchPosition(

            function (position) {

                const now = Date.now();

                /*
                 * Send immediately first time,
                 * then maximum once every 15 seconds.
                 */
                if (
                    lastSentAt !== 0 &&
                    now - lastSentAt < 15000
                ) {
                    return;
                }

                lastSentAt = now;

                const latitude =
                    position.coords.latitude;

                const longitude =
                    position.coords.longitude;

                sendLocation(
                    latitude,
                    longitude
                );
            },


            function (error) {

                console.error(
                    'GPS Error:',
                    error
                );

                let message =
                    'Unable to access your location.';

                if (error.code === 1) {

                    message =
                        'Location permission denied. Please allow location access to receive delivery offers.';

                } else if (error.code === 2) {

                    message =
                        'Your current location is unavailable.';

                } else if (error.code === 3) {

                    message =
                        'Location request timed out. Please try again.';
                }


                setStatus(
                    '● GPS Offline',
                    false
                );


                if (locationMessage) {

                    locationMessage.textContent =
                        message;
                }
            },


            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 10000
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Start Automatically
    |--------------------------------------------------------------------------
    */
    startLocationTracking();

});
</script>

@if($activeOffer)

<script>
document.addEventListener('DOMContentLoaded', function () {

    const offerBox =
        document.getElementById('delivery-offer');

    const countdown =
        document.getElementById('offer-countdown');

    if (!offerBox || !countdown) {
        return;
    }

    const expiresAt =
        new Date(
            offerBox.dataset.expiresAt
        ).getTime();

    function updateOfferCountdown() {

        const now = new Date().getTime();

        const remaining =
            Math.max(
                0,
                Math.ceil(
                    (expiresAt - now) / 1000
                )
            );

        countdown.textContent =
            remaining + 's';

        if (remaining <= 0) {

            countdown.textContent =
                'Expired';

            clearInterval(timer);

            /*
             * Reload dashboard so the expired
             * offer disappears automatically.
             */
            setTimeout(function () {

                window.location.reload();

            }, 1000);
        }
    }

    updateOfferCountdown();

    const timer =
        setInterval(
            updateOfferCountdown,
            1000
        );

});
</script>

@endif

</body>

</html>
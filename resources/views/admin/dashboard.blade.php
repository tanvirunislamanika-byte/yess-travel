<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stats Cards Row - Bootstrap Columns with Multiple Colors -->
            <div class="row mb-4">
                <!-- Total Revenue -->
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-subtitle mb-2 text-white-50 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total Revenue</h6>
                                    <h2 class="card-title text-white fw-bold mb-1" style="font-size: 1.6rem;">{{ $currencySymbol }} {{ number_format(array_sum($totalRevenue), 2) }}</h2>
                                    <small class="text-white-75">Last 12 months</small>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-dollar-sign text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="card-body text-white p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-subtitle mb-2 text-white-50 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total Users</h6>
                                    <h2 class="card-title text-white fw-bold mb-1" style="font-size: 2.5rem;">{{ number_format($total_users) }}</h2>
                                    <small class="text-white-75">Registered users</small>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-users text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Hotels -->
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <div class="card-body text-white p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-subtitle mb-2 text-white-50 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total Hotels</h6>
                                    <h2 class="card-title text-white fw-bold mb-1" style="font-size: 2.5rem;">{{ number_format($total_hotels) }}</h2>
                                    <small class="text-white-75">Active listings</small>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-hotel text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Tours -->
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <div class="card-body text-white p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-subtitle mb-2 text-white-50 fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total Tours</h6>
                                    <h2 class="card-title text-white fw-bold mb-1" style="font-size: 2.5rem;">{{ number_format($total_tours) }}</h2>
                                    <small class="text-white-75">Active packages</small>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="fas fa-map-marked-alt text-white" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Revenue Overview</h3>
                        <div class="flex items-center space-x-3">
                            <label for="revenuePeriod" class="text-sm font-medium text-gray-600">Time Period:</label>
                            <select id="revenuePeriod" class="form-select form-select-sm" style="width: auto; min-width: 120px;">
                                <option value="12">Last 12 Months</option>
                                <option value="6">Last 6 Months</option>
                                <option value="3">Last 3 Months</option>
                                <option value="1">Last Month</option>
                            </select>
                        </div>
                    </div>
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

            <!-- Charts Row - 6x6 Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                

                <!-- Daily Bookings Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Daily Bookings</h3>
                    </div>
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="dailyBookingsChart"></canvas>
                    </div>
                </div>

                <!-- Revenue Distribution Pie Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Revenue Distribution</h3>
                    </div>
                    <div class="chart-container" style="height: 250px;">
                        <canvas id="revenuePieChart"></canvas>
                    </div>
                </div>
            </div>

           

            <!-- Latest Flight Orders -->
            <div class="row mb-6">
                <div class="col-lg-12">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Latest Flight Orders</h3>
                        <a href="{{ route('admin.flight-orders') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                       <table class="table table-hover table-striped table-bordered myjobtable">
                            <thead>
                                <tr>
                                   <th>#</th>
        <th>Airline</th>
        <th>Status</th>
        <th>Passenger(s)</th>
        <th>Flight From</th>
        <th>Flight To</th>
        <th>Adults</th>
        <th>Departure</th>
        <th>Total</th>
        <th>Booked On</th>
        <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
@forelse($flight_orders as $order)
    @php
        $passengerDetails = is_string($order->passenger_details)
            ? json_decode($order->passenger_details, true)
            : $order->passenger_details;
        $passengers = array_values($passengerDetails ?? []);

        $fullNames = collect($passengers)->map(function($p) {
            return trim(($p['given_name'] ?? '') . ' ' . ($p['family_name'] ?? ''));
        })->unique()->implode(', ');

        $adultsCount = collect($passengers)->where('type', 'adults')->count();
        $nextDeparture = $order->departure_date ? \Carbon\Carbon::parse($order->departure_date)->format('d/m/Y H:i') : '-';
    @endphp
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            @if($order->airline_code ?? false)
                <img src="https://assets.duffel.com/img/airlines/for-light-background/full-color-logo/{{ $order->airline_code }}.svg"
                     alt="{{ $order->airline_code }}"
                     style="height:40px;">
            @else
                -
            @endif
        </td>
        <td>
            <span class="badge bg-{{ $order->booking_status == 'confirmed' ? 'success' : 'warning' }}">
                {{ ucfirst($order->booking_status) }}
            </span>
        </td>
        <td><b>{{ $fullNames }}</b></td>
        <td>{{ $order->origin_code ?? '-' }}</td>
        <td>{{ $order->destination_code ?? '-' }}</td>
        <td>{{ $order->adults ?? 0 }}</td>
        <td>{{ $nextDeparture }}</td>
        <td>{{ $order->currency }} {{ number_format($order->total_amount, 2) }}</td>
        <td>{{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') : '-' }}</td>
        <td>
            <a href="{{ route('admin.flight-order', $order->id) }}" class="btn btn-sm btn-primary">View</a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="11">No flight orders found.</td>
    </tr>
@endforelse
</tbody>

              </table>
                    </div>
                </div>
            </div>

            <!-- Latest Tour Bookings -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Latest Tour Bookings</h3>
                        <a href="{{ route('admin.tour-bookings.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-hover table-striped table-bordered myjobtable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="">Booking Ref</th>
                                    <th class="">Customer</th>
                                    <th class="">Tour Title</th>
                                    <th class="">Passengers</th>
                                    <th class="">Departure Date</th>
                                    <th class="">Total Amount</th>
                                    <th class="">Status</th>
                                    <th class="">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tour_bookings as $booking)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="font-mono text-xs">{{ $booking->booking_reference }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>
                                                <div class="font-medium">{{ $booking->user->name ?? 'N/A' }}</div>
                                                <div class="text-gray-500 text-xs">{{ $booking->user->email ?? 'N/A' }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="max-w-xs truncate" title="{{ $booking->tour->title ?? 'N/A' }}">
                                                {{ $booking->tour->title ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex flex-col space-y-1">
                                                @if($booking->adults > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $booking->adults }} Adults
                                                    </span>
                                                @endif
                                                @if($booking->children > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $booking->children }} Children
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($booking->departure_date)
                                                <div>
                                                    <div class="font-medium">{{ date('d M Y', strtotime($booking->departure_date)) }}</div>
                                                    <div class="text-gray-500 text-xs">{{ date('D', strtotime($booking->departure_date)) }}</div>
                                                </div>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium">{{ $currencySymbol }} {{ number_format($booking->total_amount) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.tour-bookings.show', $booking->id) }}" 
                                               class="btn btn-sm btn-primary">View Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No tour bookings found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Latest Hotel Bookings -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Latest Hotel Bookings</h3>
                        <a href="{{ route('admin.hotel-bookings') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-hover table-striped table-bordered myjobtable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="">User Name</th>
                                    <th class="">Hotel Name</th>
                                    <th class="">Travelling From</th>
                                    <th class="">Check-in Date</th>
                                    <th class="">Price</th>
                                    <th class="">Status</th>
                                    <th class="">Payment Via</th>
                                    <th class="">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($hotel_bookings as $booking)
                                    @php
                                        $user = App\Models\User::find($booking->user_id);
                                        $hotel = App\Models\ModulesData::find($booking->hotel_id);
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $hotel->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->travelling_from }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ date('d M Y l', strtotime($booking->check_in)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium">{{ $currencySymbol }} {{ number_format($booking->price, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 
                                                   ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $booking->payment_via }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.hotel-booking', $booking->id) }}" 
                                               class="btn btn-sm btn-primary">View Details</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Chart data from PHP
        const chartData = @json($chartData);
        
        // Revenue Chart with dropdown functionality
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        let revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: 'Hotels',
                        data: @json($hotelRevenue),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Flights',
                        data: @json($flightRevenue),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Tours',
                        data: @json($tourRevenue),
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '{{ $currencySymbol }}' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Handle dropdown change
        document.getElementById('revenuePeriod').addEventListener('change', function() {
            const selectedPeriod = parseInt(this.value);
            const data = chartData[selectedPeriod];
            
            if (data) {
                // Update chart data
                revenueChart.data.labels = data.months;
                revenueChart.data.datasets[0].data = data.hotelRevenue;
                revenueChart.data.datasets[1].data = data.flightRevenue;
                revenueChart.data.datasets[2].data = data.tourRevenue;
                
                // Update chart
                revenueChart.update();
            }
        });

        // Daily Bookings Chart
        const dailyCtx = document.getElementById('dailyBookingsChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'bar',
            data: {
                labels: @json($dailyDates),
                datasets: [
                    {
                        label: 'Hotels',
                        data: @json($dailyHotelBookings),
                        backgroundColor: 'rgba(147, 51, 234, 0.8)',
                        borderColor: 'rgb(147, 51, 234)',
                        borderWidth: 1
                    },
                    {
                        label: 'Flights',
                        data: @json($dailyFlightBookings),
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    },
                    {
                        label: 'Tours',
                        data: @json($dailyTourBookings),
                        backgroundColor: 'rgba(245, 101, 101, 0.8)',
                        borderColor: 'rgb(245, 101, 101)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Revenue Distribution Pie Chart
        const revenuePieCtx = document.getElementById('revenuePieChart').getContext('2d');
        new Chart(revenuePieCtx, {
            type: 'pie',
            data: {
                labels: ['Hotels', 'Flights', 'Tours'],
                datasets: [{
                    data: [
                        {{ array_sum($hotelRevenue) }},
                        {{ array_sum($flightRevenue) }},
                        {{ array_sum($tourRevenue) }}
                    ],
                    backgroundColor: [
                        'rgba(91, 51, 234, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(27, 228, 87, 0.8)'
                    ],
                    borderColor: [
                        'rgb(91, 51, 234)',
                        'rgb(239, 68, 68)',
                        'rgb(27, 228, 87)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + '{{ $currencySymbol }}' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-admin-layout> 

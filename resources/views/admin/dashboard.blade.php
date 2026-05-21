<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stats Cards Row - Bootstrap Columns with Multiple Colors -->
           <div class="row mb-4 g-4">

    <!-- Total Revenue -->
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="#" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100"
                style="min-height:170px;background: linear-gradient(135deg, #eef2ff 0%, #f8fbff 100%); transition:0.3s; cursor:pointer;">

                <div class="card-body p-4 d-flex align-items-center">

                    <div class="d-flex justify-content-between align-items-center w-100">

                        <div>
                            <h6 class="text-uppercase text-muted fw-bold mb-2" style="font-size:12px;">
                                Total Revenue
                            </h6>

                            <h3 class="fw-bold text-dark mb-1">
                                {{ $currencySymbol }} {{ number_format(array_sum($totalRevenue), 2) }}
                            </h3>

                            <small class="text-muted">Last 12 months</small>
                        </div>

                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width:60px;height:60px;background:#667eea;">
                            <i class="fas fa-dollar-sign text-white"></i>
                        </div>

                    </div>

                </div>
            </div>
        </a>
    </div>


    <!-- Total Users -->
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="#" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100"
                style="min-height:170px;background: linear-gradient(135deg, #fff0f6 0%, #f8fbff 100%); cursor:pointer;">

                <div class="card-body p-4 d-flex align-items-center">

                    <div class="d-flex justify-content-between align-items-center w-100">

                        <div>
                            <h6 class="text-uppercase text-muted fw-bold mb-2" style="font-size:12px;">
                                Total Users
                            </h6>

                            <h3 class="fw-bold text-dark mb-1">
                                {{ number_format($total_users) }}
                            </h3>

                            <small class="text-muted">Registered users</small>
                        </div>

                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width:60px;height:60px;background:#f5576c;">
                            <i class="fas fa-users text-white"></i>
                        </div>

                    </div>

                </div>
            </div>
        </a>
    </div>


    <!-- Total Hotels -->
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="#" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100"
                style="min-height:170px;background: linear-gradient(135deg, #e0f7ff 0%, #f8fbff 100%); cursor:pointer;">

                <div class="card-body p-4 d-flex align-items-center">

                    <div class="d-flex justify-content-between align-items-center w-100">

                        <div>
                            <h6 class="text-uppercase text-muted fw-bold mb-2" style="font-size:12px;">
                                Total Hotels
                            </h6>

                            <h3 class="fw-bold text-dark mb-1">
                                {{ number_format($total_hotels) }}
                            </h3>

                            <small class="text-muted">Active listings</small>
                        </div>

                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width:60px;height:60px;background:#00c6ff;">
                            <i class="fas fa-hotel text-white"></i>
                        </div>

                    </div>

                </div>
            </div>
        </a>
    </div>


    <!-- Total Tours -->
    <div class="col-lg-3 col-md-6 col-sm-12">
        <a href="#" class="text-decoration-none">
            <div class="card border-0 shadow-sm rounded-4 h-100"
                style="min-height:170px;background: linear-gradient(135deg, #e6fff3 0%, #f8fbff 100%); cursor:pointer;">

                <div class="card-body p-4 d-flex align-items-center">

                    <div class="d-flex justify-content-between align-items-center w-100">

                        <div>
                            <h6 class="text-uppercase text-muted fw-bold mb-2" style="font-size:12px;">
                                Total Tours
                            </h6>

                            <h3 class="fw-bold text-dark mb-1">
                                {{ number_format($total_tours) }}
                            </h3>

                            <small class="text-muted">Active packages</small>
                        </div>

                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                            style="width:60px;height:60px;background:#22c55e;">
                            <i class="fas fa-map-marked-alt text-white"></i>
                        </div>

                    </div>

                </div>
            </div>
        </a>
    </div>

</div>

            <!-- Revenue Overview Card -->
    <div class="card shadow-sm rounded-3 mb-4">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="fw-semibold mb-0">Revenue Overview</h5>
          <div class="d-flex align-items-center gap-2">
            <label for="revenuePeriod" class="form-label mb-0 text-muted small">Time Period:</label>
            <select id="revenuePeriod" class="form-select form-select-sm" style="width: auto; min-width: 150px;">
              <option value="12">Last 12 Months</option>
              <option value="6">Last 6 Months</option>
              <option value="3">Last 3 Months</option>
              <option value="1">Last Month</option>
            </select>
          </div>
        </div>
 
        <!-- Metric Summary -->
        <div class="row g-3 mb-4">
          <div class="col-4">
            <div class="metric-card">
              <p class="label">Total Revenue</p>
              <p class="value" id="totalRev">$84,200</p>
            </div>
          </div>
          <div class="col-4">
            <div class="metric-card">
              <p class="label">Avg / Month</p>
              <p class="value" id="avgRev">$7,017</p>
            </div>
          </div>
          <div class="col-4">
            <div class="metric-card">
              <p class="label">Growth</p>
              <p class="value green">+12.4%</p>
            </div>
          </div>
        </div>
 
        <!-- Revenue Bar Chart -->
        <div class="chart-wrapper">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>
 
    <!-- Charts Row -->
    <div class="row g-4">
 
      <!-- Daily Bookings -->
      <div class="col-md-6">
        <div class="card shadow-sm rounded-3 h-100">
          <div class="card-body p-4">
            <h6 class="fw-semibold mb-3">Daily Bookings</h6>
            <div class="chart-wrapper">
              <canvas id="dailyBookingsChart"></canvas>
            </div>
          </div>
        </div>
      </div>
 
      <!-- Revenue Distribution -->
      <div class="col-md-6">
        <div class="card shadow-sm rounded-3 h-100">
          <div class="card-body p-4">
            <h6 class="fw-semibold mb-3">Revenue Distribution</h6>
            <div class="row align-items-center g-0">
              <div class="col-7">
                <div class="chart-wrapper">
                  <canvas id="revenuePieChart"></canvas>
                </div>
              </div>
              <div class="col-5">
                <div id="pieLegend" class="d-flex flex-column gap-2 ps-2"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
 
    </div>
  </div>
 
  <script>
    // ── Revenue Bar Chart ──────────────────────────────────────
    const allMonths  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const allRevenue = [5200, 6100, 5800, 7400, 6900, 8100, 7600, 9200, 8400, 10100, 9600, 9800];
 
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    const revChart = new Chart(revCtx, {
      type: 'bar',
      data: {
        labels: allMonths,
        datasets: [{
          label: 'Revenue',
          data: allRevenue,
          backgroundColor: '#B5D4F4',
          borderColor: '#378ADD',
          borderWidth: 1.5,
          borderRadius: 5,
          hoverBackgroundColor: '#378ADD'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 11 } } },
          y: {
            grid: { color: 'rgba(0,0,0,0.06)' },
            ticks: {
              callback: v => '$' + (v / 1000).toFixed(0) + 'k',
              font: { size: 11 }
            }
          }
        }
      }
    });
 
    function updateRevChart(months) {
      const n      = parseInt(months);
      const labels = allMonths.slice(-n);
      const data   = allRevenue.slice(-n);
      revChart.data.labels              = labels;
      revChart.data.datasets[0].data   = data;
      revChart.update();
      const total = data.reduce((a, b) => a + b, 0);
      document.getElementById('totalRev').textContent = '$' + total.toLocaleString();
      document.getElementById('avgRev').textContent   = '$' + Math.round(total / n).toLocaleString();
    }
 
    document.getElementById('revenuePeriod').addEventListener('change', e => updateRevChart(e.target.value));
 
    // ── Daily Bookings Line Chart ──────────────────────────────
    const days     = Array.from({ length: 14 }, (_, i) => {
      const d = new Date();
      d.setDate(d.getDate() - 13 + i);
      return d.toLocaleDateString('en', { month: 'short', day: 'numeric' });
    });
    const bookings = [12, 18, 9, 22, 15, 28, 19, 31, 24, 17, 26, 33, 21, 29];
 
    new Chart(document.getElementById('dailyBookingsChart').getContext('2d'), {
      type: 'line',
      data: {
        labels: days,
        datasets: [{
          label: 'Bookings',
          data: bookings,
          borderColor: '#1D9E75',
          backgroundColor: 'rgba(29,158,117,0.08)',
          borderWidth: 2,
          pointBackgroundColor: '#1D9E75',
          pointRadius: 3,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { maxTicksLimit: 7, font: { size: 10 } } },
          y: { grid: { color: 'rgba(0,0,0,0.06)' }, ticks: { font: { size: 11 } } }
        }
      }
    });
 
    // ── Revenue Distribution Doughnut Chart ───────────────────
    const pieColors = ['#378ADD', '#1D9E75', '#D85A30', '#BA7517'];
    const pieLabels = ['Hotels', 'Flights', 'Tours', 'Car Hire'];
    const pieData   = [38, 27, 22, 13];
 
    new Chart(document.getElementById('revenuePieChart').getContext('2d'), {
      type: 'doughnut',
      data: {
        labels: pieLabels,
        datasets: [{
          data: pieData,
          backgroundColor: pieColors,
          borderWidth: 0,
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + '%' } }
        }
      }
    });
 
    // Pie legend
    const legendEl = document.getElementById('pieLegend');
    pieLabels.forEach((label, i) => {
      legendEl.innerHTML += `
        <div class="d-flex align-items-center gap-2">
          <span class="pie-legend-dot" style="background:${pieColors[i]};"></span>
          <span style="font-size:12px; color:#6c757d;">${label}</span>
          <span style="font-size:12px; font-weight:600; color:#1a1a2e; margin-left:auto;">${pieData[i]}%</span>
        </div>`;
    });
  </script>

           

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

<x-admin-layout>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                
            <div class="card mt-5">
<div class="card-header">
    <h3 class="card-title">FLights Booking</h3>
</div>

<div class="card-body">
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
            <a href="{{ route('admin.flight-order', $order->id) }}" class="btn btn-primary">View</a>
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
        </div>
    </div>
</x-admin-layout> 
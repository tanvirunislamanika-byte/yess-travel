<x-admin-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-white" >Booking Details</h3>
                        <div>
                            <a href="{{ route('admin.flight-orders') }}" class="btn btn-light me-2">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <a href="{{ route('admin.flight-order.pdf', $booking->id) }}" class="btn btn-light">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (isset($booking))
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h4><b>Order ID:</b> {{ $booking->offer_id ?? '-' }}</h4>
                                    <p><b>Status:</b> <span
                                            class="badge text-bg-{{ $booking->booking_status == 'confirmed' ? 'success' : 'warning' }}">{{ ucfirst($booking->booking_status) }}</span>
                                    </p>
                                    <p><b>Amount:</b> {{ $booking->currency }}
                                        {{ number_format($booking->total_amount, 2) }}</p>
                                    <p><b>Created:</b>
                                        {{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('d/m/Y H:i') : '-' }}
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    @if ($booking->airline_code)
                                        <img src="https://assets.duffel.com/img/airlines/for-light-background/full-color-logo/{{ $booking->airline_code }}.svg"
                                            alt="{{ $booking->airline_code }}" style="height:40px;">
                                        <h4 class="mt-2">{{ $booking->airline_name }}</h4>
                                    @endif
                                </div>
                            </div>

                            @php
                                $offer = json_decode($booking->flight_info, true);
                            @endphp

                            <div class="col-md-12">
                                @if (isset($offer) && $offer != null)
                                    <div class="dflflightbox">
                                        <div class="titledfl">Flight Summary</div>
                                        <div class="dflintbx">
                                            <div class="lead"><strong>
                                                    {{ $offer['slices'][0]['origin']['city_name'] ?? '' }}
                                                    ({{ $offer['slices'][0]['origin']['iata_code'] ?? '' }})</strong>
                                                <i class="fas fa-arrows-alt-h"></i>
                                                <strong>{{ $offer['slices'][0]['destination']['city_name'] ?? '' }}
                                                    ({{ $offer['slices'][0]['destination']['iata_code'] ?? '' }})</strong>
                                            </div>
                                            <div class="datedfl">
                                                {{ \Carbon\Carbon::parse($offer['slices'][0]['segments'][0]['departing_at'])->format('D, d M') }}
                                                -
                                                {{ \Carbon\Carbon::parse($offer['slices'][count($offer['slices']) - 1]['segments'][0]['arriving_at'])->format(
                                                    'D, d M Y',
                                                ) }}
                                                - {{ count($offer['passengers'] ?? []) }} passenger(s)
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Flight Details -->
                                    @foreach($offer['slices'] as $index => $slice)
                                        <div class="flight-details mb-4">
                                            <h5 class="mb-3">{{ $index === 0 ? 'Outbound Flight' : 'Return Flight' }}</h5>
                                            <div class="row">
                                                @foreach($slice['segments'] as $segment)
                                                    <div class="col-md-12 mb-3">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <p class="small mb-1">
                                                                    <i class="far fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($segment['departing_at'])->format('H:i') }}
                                                                    -
                                                                    {{ \Carbon\Carbon::parse($segment['arriving_at'])->format('H:i') }}
                                                                    ({{ $segment['duration'] ?? 'N/A' }})
                                                                </p>
                                                                <p class="small mb-1">
                                                                    <i class="fas fa-plane"></i>
                                                                    {{ $segment['operating_carrier']['name'] ?? '' }} -
                                                                    {{ $segment['operating_carrier']['flight_number'] ?? '' }}
                                                                    @if (isset($segment['aircraft']['name']))
                                                                        <br>
                                                                        <span>Aircraft: {{ $segment['aircraft']['name'] }}
                                                                            ({{ $segment['aircraft']['iata_code'] ?? '' }})</span>
                                                                    @endif
                                                                </p>
                                                                <p class="small mb-1">
                                                                    <strong>Flight Number:</strong>
                                                                    {{ $segment['operating_carrier_flight_number'] ?? '-' }}
                                                                </p>
                                                                <div class="small d-flex gap-2 align-items-center mb-1">
                                                                    <i class="fas fa-plane-departure"></i>
                                                                    <div class="">
                                                                        {{ $segment['origin']['name'] ?? '' }}
                                                                        @if (isset($segment['origin_terminal']))
                                                                            <br><span>Terminal:
                                                                                {{ $segment['origin_terminal'] }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="small d-flex gap-2 align-items-center mb-1">
                                                                    <i class="fas fa-plane-arrival"></i>
                                                                    <div class="">
                                                                        {{ $segment['destination']['name'] ?? '' }}
                                                                        @if (isset($segment['destination_terminal']))
                                                                            <br><span>Terminal:
                                                                                {{ $segment['destination_terminal'] }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @if (isset($segment['passengers'][0]))
                                                                    <p class="small mb-1">
                                                                        <i class="fas fa-chair"></i>
                                                                        Cabin:
                                                                        {{ $segment['passengers'][0]['cabin_class_marketing_name'] ?? ($booking->cabin_class ?? 'N/A') }}
                                                                    </p>
                                                                    @if (isset($segment['passengers'][0]['baggages']) && is_array($segment['passengers'][0]['baggages']))
                                                                        <p class="small mb-1">
                                                                            <i class="fas fa-suitcase"></i>
                                                                            @foreach ($segment['passengers'][0]['baggages'] as $baggage)
                                                                                {{ ucfirst($baggage['type']) }} bag:
                                                                                {{ $baggage['quantity'] ?? 1 }}
                                                                                @if (!$loop->last)
                                                                                    ,
                                                                                @endif
                                                                            @endforeach
                                                                        </p>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if (!$loop->last)
                                                        <div class="col-md-12 text-center"><i class="fas fa-arrow-down"></i>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <div class="col-md-12">
                                                    <p class="small"><strong>Flight type:</strong>
                                                        {{ count($slice['segments']) === 1 ? 'Non-stop' : 'With stops' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Passenger Details -->
                                    <div class="passenger-details mb-4">
                                        <h5 class="mb-3">Passenger Details</h5>
                                        <div class="row">
                                            @foreach($passengerDetails as $passenger)
                                                <div class="col-md-6 mb-4">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p class="mb-2">
                                                                        <strong>Name:</strong><br>
                                                                        {{ $passenger['title'] ?? '' }} {{ $passenger['given_name'] ?? '' }} {{ $passenger['family_name'] ?? '' }}
                                                                    </p>
                                                                    <p class="mb-2">
                                                                        <strong>Date of Birth:</strong><br>
                                                                        {{ $passenger['born_on'] ?? '' }}
                                                                    </p>
                                                                    <p class="mb-2">
                                                                        <strong>Gender:</strong><br>
                                                                        {{ $passenger['gender'] ?? '' }}
                                                                    </p>
                                                                    <p class="mb-2">
                                                                        <strong>Type:</strong><br>
                                                                        {{ isset($passenger['type']) ? ucfirst($passenger['type']) : 'Adult' }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p class="mb-2">
                                                                        <strong>Email:</strong><br>
                                                                        {{ $passenger['email'] ?? '' }}
                                                                    </p>
                                                                    <p class="mb-2">
                                                                        <strong>Phone:</strong><br>
                                                                        {{ $passenger['phone_number'] ?? '' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Passport Information -->
                                                            @if (isset($passenger['passport_number']))
                                                            <hr class="my-3">
                                                            <h6 class="text-primary mb-3">
                                                                <i class="fas fa-passport"></i> Passport Information
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p class="mb-2">
                                                                        <strong>Passport Number:</strong><br>
                                                                        {{ $passenger['passport_number'] ?? 'N/A' }}
                                                                    </p>
                                                                    <p class="mb-2">
                                                                        <strong>Issue Date:</strong><br>
                                                                        {{ $passenger['passport_issue_date'] ?? 'N/A' }}
                                                                    </p>
                                                                    <p class="mb-2">
                                                                        <strong>Expiry Date:</strong><br>
                                                                        {{ $passenger['passport_expiry_date'] ?? 'N/A' }}
                                                                    </p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p class="mb-2">
                                                                        <strong>Issuing Country:</strong><br>
                                                                        @php
                                                                            $issuingCountry = \App\Models\Country::find($passenger['passport_issuing_country'] ?? null);
                                                                        @endphp
                                                                        {{ $issuingCountry->name ?? 'N/A' }}
                                                                    </p>
                                                                    <p class="mb-2">
                                                                        <strong>Country of Residence:</strong><br>
                                                                        @php
                                                                            $residenceCountry = \App\Models\Country::find($passenger['country_of_residence'] ?? null);
                                                                        @endphp
                                                                        {{ $residenceCountry->name ?? 'N/A' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        Flight details not available.
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-danger">
                                Booking not found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 
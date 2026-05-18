<x-app-layout>
    <div class="booking-details-container">
        <div class="container">
            @if (isset($booking))
                <!-- Header Section -->
                <div class="booking-details-header">
                    <div class="booking-id">
                        <h1>Oder ID: {{ $booking->offer_id ?? 'Booking Details' }}</h1>                       
                    </div>
                    
                    <div class="action-buttons">
                        @if ($booking->payment_status == 'paid' && $booking->booking_status == 'confirmed')
                            <a href="{{ route('flights.booking.pdf', $booking->id) }}" class="btn btn-sec">
                                <i class="fas fa-download"></i>
                                Download Ticket in PDF
                            </a>
                        @endif
                        
                        @if ($booking->payment_status == 'pending' && $booking->booking_status == 'hold')
                            @if (!empty($booking->order_expire_at) && \Carbon\Carbon::parse($booking->order_expire_at)->isFuture())
                                <a href="{{ route('flights.payment', $booking->id) }}" class="btn btn-primary">
                                    <i class="fas fa-credit-card"></i>
                                    Pay Now
                                </a>
                            @else
                                <span class="btn btn-danger disabled">
                                    <i class="fas fa-clock"></i>
                                    Expired
                                </span>
                            @endif
                        @endif
                        
                        <a href="{{url('flights')}}" class="action-btn primary">
                            <i class="fas fa-search"></i>
                            Search More Flights
                        </a>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="booking-details-content">
                    <!-- Left Column - Main Content -->
                    <div class="booking-details-main">
                        @php
                            $offer = json_decode($booking->flight_info, true);
                        @endphp

                        @if (isset($offer) && $offer != null)
                            <!-- Journey Section -->
                            <div class="journey-section">
                                <div class="journey-header">
                                    <div class="airline-logo">
                                        @if ($booking->airline_code)
                                            <img src="https://assets.duffel.com/img/airlines/for-light-background/full-color-logo/{{ $booking->airline_code }}.svg"
                                                alt="{{ $booking->airline_code }}">
                                        @else
                                            <i class="fas fa-plane"></i>
                                        @endif
                                    </div>
                                    <div class="journey-info">
                                        <h3>{{ $offer['slices'][0]['origin']['iata_code'] ?? 'N/A' }} - {{ $offer['slices'][0]['destination']['iata_code'] ?? 'N/A' }}</h3>
                                        <div class="route">{{ $offer['slices'][0]['origin']['city_name'] ?? 'Origin' }} to {{ $offer['slices'][0]['destination']['city_name'] ?? 'Destination' }}</div>
                                        <div class="class">{{ ucfirst($offer['slices'][0]['passengers'][0]['cabin_class_marketing_name'] ?? 'Economy') }} · {{ $booking->airline_name ?? 'Airline' }}</div>
                                    </div>
                                </div>

                                
                                    @foreach ($offer['slices'] as $sliceIndex => $slice)
                                        @php
                                            $departureTime = \Carbon\Carbon::parse($slice['segments'][0]['departing_at']);
                                            $arrivalTime = \Carbon\Carbon::parse($slice['segments'][0]['arriving_at']);
                                            $duration = $departureTime->diffForHumans($arrivalTime, ['parts' => 2]);
                                            $isNonStop = count($slice['segments']) === 1;
                                        @endphp
                                        <div class="journey-timeline">
                                        <!-- Departure -->
                                        <div class="timeline-item departure">
                                            <div class="time">{{ $departureTime->format('D, j M Y') }}, {{ $departureTime->format('H:i') }}</div>                                            
                                            <div class="location">Depart from: {{ $slice['segments'][0]['origin']['name'] ?? 'Airport' }} ({{ $slice['segments'][0]['origin']['iata_code'] ?? 'N/A' }})  - 
                                            
                                            @if (isset($slice['segments'][0]['origin_terminal']))
                                                Terminal {{ $slice['segments'][0]['origin_terminal'] }}
                                            @endif
                                            </div>                                         
                                            
                                        </div>

                                        <!-- Flight Duration -->
                                        <div class="flight-duration">
                                            <div class="duration">Flight Duration: {{ $duration }}</div>
                                            <div class="route">{{ $slice['segments'][0]['origin']['iata_code'] ?? 'N/A' }} - {{ $slice['segments'][0]['destination']['iata_code'] ?? 'N/A' }} - {{ $isNonStop ? 'Non-stop' : 'With stops' }}</div>
                                           
                                        </div>

                                        <!-- Arrival -->
                                        <div class="timeline-item arrival">
                                            <div class="time">{{ $arrivalTime->format('D, j M Y') }}, {{ $arrivalTime->format('H:i') }}</div>                                            
                                            <div class="location">Arrive at: {{ $slice['segments'][0]['destination']['name'] ?? 'Airport' }} ({{ $slice['segments'][0]['destination']['iata_code'] ?? 'N/A' }}) -                                  
                                            @if (isset($slice['segments'][0]['destination_terminal']))
                                                Terminal {{ $slice['segments'][0]['destination_terminal'] }}
                                            @endif
                                        </div>
                                        </div>
                                        </div>
                                    @endforeach
                               
                            </div>

                            <!-- Flight Details -->
                            <div class="flight-detailsbox">
                                <h4>Flight Information</h4>
                                <div class="flight-details-grid">
                                    <div class="flight-detail-item">
                                        <i class="fas fa-plane"></i>
                                        <div class="detail-content">
                                            <div class="label">Airline</div>
                                            <div class="value">{{ $booking->airline_name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flight-detail-item">
                                        <i class="fas fa-plane-departure"></i>
                                        <div class="detail-content">
                                            <div class="label">Flight Number</div>
                                            <div class="value">{{ $offer['slices'][0]['segments'][0]['operating_carrier_flight_number'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flight-detail-item">
                                        <i class="fas fa-chair"></i>
                                        <div class="detail-content">
                                            <div class="label">Cabin Class</div>
                                            <div class="value">{{ ucfirst($offer['slices'][0]['passengers'][0]['cabin_class_marketing_name'] ?? 'Economy') }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flight-detail-item">
                                        <i class="fas fa-suitcase"></i>
                                        <div class="detail-content">
                                            <div class="label">Checked Baggage</div>
                                            <div class="value">
                                                @if (!empty($offer['slices'][0]['passengers'][0]['baggages']))
                                                    @foreach ($offer['slices'][0]['passengers'][0]['baggages'] as $baggage)
                                                        @if ($baggage['type'] === 'checked')
                                                            {{ $baggage['quantity'] ?? 1 }} bag
                                                        @endif
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flight-detail-item">
                                        <i class="fas fa-briefcase"></i>
                                        <div class="detail-content">
                                            <div class="label">Carry-on</div>
                                            <div class="value">
                                                @if (!empty($offer['slices'][0]['passengers'][0]['baggages']))
                                                    @foreach ($offer['slices'][0]['passengers'][0]['baggages'] as $baggage)
                                                        @if ($baggage['type'] === 'carry_on')
                                                            {{ $baggage['quantity'] ?? 1 }} bag
                                                        @endif
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if (isset($offer['slices'][0]['segments'][0]['aircraft']['name']))
                                    <div class="flight-detail-item">
                                        <i class="fas fa-plane"></i>
                                        <div class="detail-content">
                                            <div class="label">Aircraft</div>
                                            <div class="value">{{ $offer['slices'][0]['segments'][0]['aircraft']['name'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                             <!-- Passengers Section -->
                             <div class="passengers-section">
                                <h4>Passengers</h4>
                                @foreach ($passengers as $passengerIndex => $passenger)
                                    <div class="passenger-card">
                                        <div class="passenger-header">
                                            <span class="passenger-type">{{ ucfirst($passenger['type'] ?? 'Adult') }} {{ $passengerIndex + 1 }}</span>
                                            <div class="passenger-name">
                                                {{ $passenger['title'] ?? '' }} {{ $passenger['given_name'] ?? '' }} {{ $passenger['family_name'] ?? '' }}
                                            </div>
                                        </div>
                                        <div class="passenger-details">
                                            <div class="passenger-detail-item">
                                                <i class="fas fa-envelope"></i>
                                                <div>
                                                    <div class="label">Email</div>
                                                    <div class="value">{{ $passenger['email'] ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                            <div class="passenger-detail-item">
                                                <i class="fas fa-phone"></i>
                                                <div>
                                                    <div class="label">Phone</div>
                                                    <div class="value">{{ $passenger['phonecode'] ?? '' }} {{ $passenger['phone_number'] ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                            <div class="passenger-detail-item">
                                                <i class="fas fa-birthday-cake"></i>
                                                <div>
                                                    <div class="label">Date of Birth</div>
                                                    <div class="value">{{ $passenger['born_on'] ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                            <div class="passenger-detail-item">
                                                <i class="fas fa-user"></i>
                                                <div>
                                                    <div class="label">Gender</div>
                                                    <div class="value">{{ ucfirst($passenger['gender'] ?? 'N/A') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Passport Information -->
                                        @if (isset($passenger['passport_number']))
                                        <div class="passport-info-section">
                                            <h6><i class="fas fa-passport"></i> Passport Information</h6>
                                            <div class="passenger-details">
                                                <div class="passenger-detail-item">
                                                    <i class="fas fa-id-card"></i>
                                                    <div>
                                                        <div class="label">Passport Number</div>
                                                        <div class="value">{{ $passenger['passport_number'] ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                                <div class="passenger-detail-item">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <div>
                                                        <div class="label">Issue Date</div>
                                                        <div class="value">{{ $passenger['passport_issue_date'] ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                                <div class="passenger-detail-item">
                                                    <i class="fas fa-calendar-times"></i>
                                                    <div>
                                                        <div class="label">Expiry Date</div>
                                                        <div class="value">{{ $passenger['passport_expiry_date'] ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                                <div class="passenger-detail-item">
                                                    <i class="fas fa-flag"></i>
                                                    <div>
                                                        <div class="label">Issuing Country</div>
                                                        <div class="value">
                                                            @php
                                                                $issuingCountry = \App\Models\Country::find($passenger['passport_issuing_country'] ?? null);
                                                            @endphp
                                                            {{ $issuingCountry->name ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="passenger-detail-item">
                                                    <i class="fas fa-home"></i>
                                                    <div>
                                                        <div class="label">Country of Residence</div>
                                                        <div class="value">
                                                            @php
                                                                $residenceCountry = \App\Models\Country::find($passenger['country_of_residence'] ?? null);
                                                            @endphp
                                                            {{ $residenceCountry->name ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Policies Section -->
                            <div class="policies-section">
                                <h4>Policies</h4>
                                <div class="policy-item">
                                    <i class="fas fa-exchange-alt"></i>
                                    <div class="policy-content">
                                        <h6>Flight Change Policy</h6>
                                        <p>Make changes to this flight up until the departure date (a change penalty may apply)</p>
                                    </div>
                                </div>
                                <div class="policy-item">
                                    <i class="fas fa-edit"></i>
                                    <div class="policy-content">
                                        <h6>Order Change Policy</h6>
                                        <p>Make changes to this order up until the initial departure date (a change penalty may apply)</p>
                                    </div>
                                </div>
                                <div class="policy-item">
                                    <i class="fas fa-undo"></i>
                                    <div class="policy-content">
                                        <h6>Refund Policy</h6>
                                        <p>This order may be refundable subject to airline terms and conditions</p>
                                    </div>
                                </div>
                            </div>

                           
                        @endif
                    </div>

                    <!-- Right Column - Sidebar -->
                    <div class="booking-details-sidebar">
                        <!-- Summary Section -->
                        <div class="summary-section">
                            <h4>Summary</h4>
                            <div class="summary-item">
                                <span class="label">Order ID</span>
                                <span class="value">{{ $booking->offer_id ?? 'N/A' }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Status</span>
                                <span class="status {{ $booking->booking_status }}">{{ ucfirst($booking->booking_status) }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Airline</span>
                                <span class="value">{{ $booking->airline_name ?? 'N/A' }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Created</span>
                                <span class="value">{{ $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('D, j M Y, H:i') : 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        @if (isset($offer))
                        <div class="summary-section">
                            <h4>Price Breakdown</h4>
                            <div class="price-breakdown">
                                <h5>Fare Details</h5>
                                @php
                                    $baseAmount = $offer['base_amount'] ?? 0;
                                    $taxAmount = $offer['tax_amount'] ?? 0;
                                    $currency = $offer['total_currency'] ?? 'USD';
                                    $totalServiceAmount = $booking->services_total ?? 0;
                                @endphp
                                
                                <div class="price-item">
                                    <span class="label">Base Fare</span>
                                    <span class="value">{{ $currency }} {{ number_format($baseAmount, 2) }}</span>
                                </div>
                                <div class="price-item">
                                    <span class="label">Taxes & Fees</span>
                                    <span class="value">{{ $currency }} {{ number_format($taxAmount, 2) }}</span>
                                </div>
                                <div class="price-item">
                                    <span class="label">Service Fee</span>
                                    <span class="value">{{ $currency }} {{ number_format($booking->service_charges ?? 0, 2) }}</span>
                                </div>
                                @if ($totalServiceAmount > 0)
                                <div class="price-item">
                                    <span class="label">Extra Services</span>
                                    <span class="value">{{ $currency }} {{ number_format($totalServiceAmount, 2) }}</span>
                                </div>
                                @endif
                                <div class="price-item total">
                                    <span class="label">Total</span>
                                    <span class="value">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="alert alert-danger">
                    <h4>Booking Not Found</h4>
                    <p>The requested booking information could not be found.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const btn = event.target.closest('.copy-btn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i>';
                btn.style.background = '#28a745';
                btn.style.color = '#fff';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.style.color = '';
                }, 2000);
            });
        }
    </script>
</x-app-layout>

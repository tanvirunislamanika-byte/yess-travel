<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Flight Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('images/' . widget(1)->extra_image_1) }}" alt="{{ widget(1)->extra_field_1 }}">

        <h3>Flight Booking Confirmation</h3>
        <p>Order ID: {{ $booking->offer_id ?? '-' }}</p>
    </div>

    <div class="section-title">Booking Information</div>
    <table>
        <tr>
            <th>Status</th>
            <th>Total Amount</th>
            <th>Booking Date</th>
        </tr>
        <tr>
            <td>{{ ucfirst($booking->booking_status) }}</td>
            <td>{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</td>
            <td>{{ $booking->created_at->format('d/m/Y H:i A') }}</td>
        </tr>
    </table>

    @php $offer = json_decode($booking->flight_info, true); @endphp
    @if (!empty($offer))
        <div class="section-title">Flight Summary</div>
        <table>
            <tr>
                <th>Route</th>
                <th>Date</th>
                <th>Passengers</th>
            </tr>
            <tr>
                <td>
                    {{ $offer['slices'][0]['origin']['city_name'] ?? '' }}
                    ({{ $offer['slices'][0]['origin']['iata_code'] ?? '' }}) -
                    {{ $offer['slices'][0]['destination']['city_name'] ?? '' }}
                    ({{ $offer['slices'][0]['destination']['iata_code'] ?? '' }})
                </td>
                <td>
                    @php
                        $lastSlice = count($offer['slices']) > 0 ? $offer['slices'][count($offer['slices']) - 1] : $offer['slices'][0];
                    @endphp
                    {{ \Carbon\Carbon::parse($offer['slices'][0]['segments'][0]['departing_at'])->format('d M Y h:i A') }} -
                    {{ \Carbon\Carbon::parse($lastSlice['segments'][0]['arriving_at'])->format('d M Y h:i A') }}
                </td>
                <td>{{ count($offer['passengers'] ?? []) }}</td>
            </tr>
        </table>

        <div class="section-title">Ticket Details</div>
        
        @foreach ($offer['slices'] as $sliceIndex => $slice)
            <table>
                <tr>
                    <th colspan="2">{{ $sliceIndex == 0 ? 'DEPART' : 'RETURN' }}:
                        {{ $slice['origin']['iata_code'] ?? '' }} -> {{ $slice['destination']['iata_code'] ?? '' }}
                    </th>
                </tr>
                @foreach ($slice['segments'] as $segment)
                    <tr>
                        <td><strong>Time</strong></td>
                        <td>{{ \Carbon\Carbon::parse($segment['departing_at'])->format('d M Y h:i A') }} ->
                            {{ \Carbon\Carbon::parse($segment['arriving_at'])->format('d M Y h:i A') }}
                            ({{ $segment['duration'] ?? 'N/A' }})</td>
                    </tr>
                    <tr>
                        <td><strong>Airline</strong></td>
                        <td>{{ $segment['operating_carrier']['name'] ?? '' }}
                            {{ $segment['operating_carrier']['flight_number'] ?? '' }}
                            @if (isset($segment['aircraft']['name']))
                                <br>Aircraft: {{ $segment['aircraft']['name'] }}
                                ({{ $segment['aircraft']['iata_code'] ?? '' }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>From</strong></td>
                        <td>{{ $segment['origin']['name'] ?? '' }} ({{ $slice['origin']['iata_code'] ?? 'N/A' }})
                            @if (isset($segment['origin_terminal']))
                                <br>Terminal: {{ $segment['origin_terminal'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>To</strong></td>
                        <td>{{ $segment['destination']['name'] ?? '' }} ({{ $slice['destination']['iata_code'] ?? 'N/A' }})
                            @if (isset($segment['destination_terminal']))
                                <br>Terminal: {{ $segment['destination_terminal'] }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Cabin</strong></td>
                        <td>{{ $segment['passengers'][0]['cabin_class_marketing_name'] ?? 'N/A' }}</td>
                    </tr>
                    @if (isset($segment['passengers'][0]['baggages']))
                        <tr>
                            <td><strong>Baggage</strong></td>
                            <td>
                                @foreach ($segment['passengers'][0]['baggages'] as $baggage)
                                    {{ ucfirst($baggage['type']) }}: {{ $baggage['quantity'] ?? 1 }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @endforeach
    @endif

    <div class="section-title">Passenger Details</div>
    @if(!empty($passengers))
        @foreach($passengers as $passenger)
            <table>
                <tr>
                    <th colspan="2">Passenger {{ $loop->iteration }}: {{ $passenger['title'] ?? '' }} {{ $passenger['given_name'] ?? '' }} {{ $passenger['family_name'] ?? '' }} ({{ isset($passenger['type']) ? ucfirst($passenger['type']) : 'Adult' }})</th>
                </tr>
                <tr>
                    <td><strong>Date of Birth</strong></td>
                    <td>{{ $passenger['born_on'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Gender</strong></td>
                    <td>{{ strtoupper($passenger['gender'] ?? 'N/A') }}</td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td>{{ $passenger['email'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Phone</strong></td>
                    <td>{{ ($passenger['phonecode'] ?? '') }} {{ $passenger['phone_number'] ?? 'N/A' }}</td>
                </tr>
                @if(isset($passenger['passport_number']))
                    <tr>
                        <td colspan="2" style="background-color: #e8f4f8; font-weight: bold; padding-top: 10px;">
                            <i>🛂 Passport Information</i>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Passport Number</strong></td>
                        <td>{{ $passenger['passport_number'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Issue Date</strong></td>
                        <td>{{ $passenger['passport_issue_date'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Expiry Date</strong></td>
                        <td>{{ $passenger['passport_expiry_date'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Issuing Country</strong></td>
                        <td>
                            @php
                                $issuingCountry = \App\Models\Country::find($passenger['passport_issuing_country'] ?? null);
                            @endphp
                            {{ $issuingCountry->name ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Country of Residence</strong></td>
                        <td>
                            @php
                                $residenceCountry = \App\Models\Country::find($passenger['country_of_residence'] ?? null);
                            @endphp
                            {{ $residenceCountry->name ?? 'N/A' }}
                        </td>
                    </tr>
                @endif
            </table>
        @endforeach
    @endif

    <div class="section">
        <div class="section-title">Important Information</div>
        <p>Please arrive at the airport at least 2 hours before your flight departure time.</p>
        <p>Make sure to carry valid identification documents and passports for all passengers.</p>
        <p>For any queries or assistance, please contact our customer support.</p>
    </div>

</body>

</html>

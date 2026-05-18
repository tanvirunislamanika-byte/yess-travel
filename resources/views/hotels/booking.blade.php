<x-app-layout>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('frontend.book_hotel') }}: {{ $hotel->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('hotel.booking.store', ['hotel_id' => $hotel->id]) }}" method="post">
                            @csrf
                            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                            <input type="hidden" id="price" name="price" value="">
                            
                            <div class="mb-3">
                                <label for="travelling_from" class="form-label">{{ __('frontend.travelling_from') }}</label>
                                <input type="text" class="form-control @error('travelling_from') is-invalid @enderror" 
                                    id="travelling_from" name="travelling_from" value="{{ old('travelling_from') }}" required>
                                @error('travelling_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="check_in" class="form-label">{{ __('frontend.check_in') }}</label>
                                        <input type="date" class="form-control @error('check_in') is-invalid @enderror" 
                                            id="check_in" name="check_in" value="{{ old('check_in') }}" required>
                                        @error('check_in')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="check_out" class="form-label">{{ __('frontend.check_out') }}</label>
                                        <input type="date" class="form-control @error('check_out') is-invalid @enderror" 
                                            id="check_out" name="check_out" value="{{ old('check_out') }}" required>
                                        @error('check_out')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="adults" class="form-label">{{ __('frontend.adults') }} (18+ {{ __('frontend.years') }})</label>
                                        <select class="form-control @error('adults') is-invalid @enderror" 
                                            id="adults" name="adults" required>
                                            @for($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ old('adults') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('adults')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="childrens" class="form-label">{{ __('frontend.children') }} (0-12 {{ __('frontend.years') }})</label>
                                        <select class="form-control @error('childrens') is-invalid @enderror" 
                                            id="childrens" name="childrens" required>
                                            @for($i = 0; $i <= 10; $i++)
                                                <option value="{{ $i }}" {{ old('childrens') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('childrens')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="rooms" class="form-label">{{ __('frontend.rooms') }}</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decrementRooms()">-</button>
                                    <input type="number" class="form-control text-center @error('rooms') is-invalid @enderror" 
                                        id="rooms" name="rooms" value="{{ old('rooms', 1) }}" min="1" max="10" required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="incrementRooms()">+</button>
                                </div>
                                <small class="text-muted">{{ __('frontend.charges_per_night_per_room') }}</small>
                                @error('rooms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <h5>{{ __('frontend.total_payment') }}: <span id="totalAmount">{{ \App\Helpers\CurrencyHelper::formatPrice($hotel->extra_field_1) }}</span></h5>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('frontend.proceed_to_payment') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('frontend.booking_summary') }}</h4>
                    </div>
                    <div class="card-body">
                        <h5>{{ $hotel->title }}</h5>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt"></i> {{ $hotel->extra_field_18 }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-hotel"></i> {{ title($hotel->extra_field_2) }}
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-star"></i> {{ title($hotel->extra_field_23) }}
                        </p>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('frontend.price_per_room') }}:</span>
                            <span>{{ \App\Helpers\CurrencyHelper::formatPrice($hotel->extra_field_1) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('frontend.number_of_rooms') }}:</span>
                            <span id="summaryRooms">1</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('frontend.number_of_nights') }}</span>
                            <span id="summaryNights">1</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>{{ __('frontend.total_amount') }}:</strong>
                            <strong><span id="summaryTotal">{{ \App\Helpers\CurrencyHelper::formatPrice($hotel->extra_field_1) }}</span></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        function incrementRooms() {
            const input = document.getElementById('rooms');
            if (input.value < 10) {
                input.value = parseInt(input.value) + 1;
                updateSummary();
            }
        }

        function decrementRooms() {
            const input = document.getElementById('rooms');
            if (input.value > 1) {
                input.value = parseInt(input.value) - 1;
                updateSummary();
            }
        }

        function updateSummary() {
            const rooms = parseInt(document.getElementById('rooms').value);
            const checkIn = new Date(document.getElementById('check_in').value);
            const checkOut = new Date(document.getElementById('check_out').value);
            const roomPrice = parseFloat("{{ filter_var($hotel->extra_field_1, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) }}");

            if (!isNaN(checkIn) && !isNaN(checkOut)) {
                const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                const totalAmount = rooms * roomPrice * nights;

                document.getElementById('summaryRooms').textContent = rooms;
                document.getElementById('summaryNights').textContent = nights;
                document.getElementById('summaryTotal').textContent = totalAmount.toFixed(2);
                document.getElementById('totalAmount').textContent = totalAmount.toFixed(2);
                document.getElementById('price').value = totalAmount.toFixed(2);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('check_in').setAttribute('min', today);
            document.getElementById('check_out').setAttribute('min', today);

            document.getElementById('check_in').addEventListener('change', function() {
                document.getElementById('check_out').setAttribute('min', this.value);
                updateSummary();
            });

            document.getElementById('check_out').addEventListener('change', updateSummary);
            document.getElementById('rooms').addEventListener('change', updateSummary);

            // Set initial dates if not set
            if (!document.getElementById('check_in').value) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('check_in').value = tomorrow.toISOString().split('T')[0];
            }
            if (!document.getElementById('check_out').value) {
                const dayAfterTomorrow = new Date();
                dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2);
                document.getElementById('check_out').value = dayAfterTomorrow.toISOString().split('T')[0];
            }

            updateSummary();
        });
    </script>
    @endpush
</x-app-layout> 
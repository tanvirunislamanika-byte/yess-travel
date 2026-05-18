<div class="flight-list" id="flight-list">
    @foreach($flights as $flight)
        <div class="flight-item">
            <!-- Existing flight item content -->
        </div>
    @endforeach
</div>

<div id="loading" style="display: none; text-align: center; padding: 20px;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@push('scripts')
<script>
let currentPage = 1;
let loading = false;
let hasMore = true;

// Function to check if we're near the bottom of the page
function isNearBottom() {
    return window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100;
}

// Function to load more flights
async function loadMoreFlights() {
    if (loading || !hasMore) return;
    
    loading = true;
    document.getElementById('loading').style.display = 'block';
    
    try {
        const response = await fetch(`{{ route('flight.search') }}?page=${currentPage + 1}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.offers && data.offers.length > 0) {
            const flightList = document.getElementById('flight-list');
            
            data.offers.forEach(flight => {
                const flightHtml = `
                    <div class="flight-item">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="flight-logo">
                                    <img src="${flight.airline_logo}" alt="${flight.airline_name}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="flight-details">
                                    <div class="flight-time">
                                        <span>${flight.departure_time}</span>
                                        <span class="flight-duration">${flight.duration}</span>
                                        <span>${flight.arrival_time}</span>
                                    </div>
                                    <div class="flight-route">
                                        ${flight.origin} → ${flight.destination}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="flight-price">
                                    <span class="price">${flight.price}</span>
                                    <button class="btn btn-primary select-flight" data-offer-id="${flight.offer_id}">
                                        Select
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                flightList.insertAdjacentHTML('beforeend', flightHtml);
            });
            
            currentPage++;
            hasMore = currentPage < data.last_page;
        } else {
            hasMore = false;
        }
    } catch (error) {
        console.error('Error loading more flights:', error);
    } finally {
        loading = false;
        document.getElementById('loading').style.display = 'none';
    }
}

// Add scroll event listener
window.addEventListener('scroll', () => {
    if (isNearBottom()) {
        loadMoreFlights();
    }
});

// Initial check for scroll position
if (isNearBottom()) {
    loadMoreFlights();
}
</script>
@endpush 
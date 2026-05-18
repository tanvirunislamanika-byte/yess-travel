{{-- Location Fields Component for Module Forms --}}
@php
    // Get location data
    $countries = \App\Models\Country::select('name', 'id')->orderBy('name')->pluck('name', 'id')->toArray();
    $states = \App\Models\State::select('name', 'id')->orderBy('name')->pluck('name', 'id')->toArray();
    $cities = \App\Models\City::select('name', 'id')->orderBy('name')->pluck('name', 'id')->toArray();
@endphp

@push('js')
<script type="text/javascript">
    // Base URL for API calls
    var apiBaseUrl = '{{ url("/") }}';
    
    // Location data for JavaScript
    var locationData = {
        countries: @json($countries),
        states: @json($states),
        cities: @json($cities)
    };
    
    // Function to populate countries dropdown
    function populateCountries(fieldId) {
        var $field = $('#' + fieldId);
        if (!$field.length) return;
        
        $field.empty();
        $field.append('<option value="">Select Country</option>');
        
        $.each(locationData.countries, function(id, name) {
            $field.append('<option value="' + id + '">' + name + '</option>');
        });
    }
    
    // Function to populate states by country
    function populateStatesByCountry(fieldId, countryId) {
        var $field = $('#' + fieldId);
        if (!$field.length) return;
        
        $field.empty();
        $field.append('<option value="">Select State/Province</option>');
        
        if (countryId) {
            $.get(apiBaseUrl + '/api/v1/states/by-country/' + countryId, function(response) {
                if (response.success && response.data) {
                    $.each(response.data, function(index, state) {
                        $field.append('<option value="' + state.id + '">' + state.name + '</option>');
                    });
                }
            });
        }
    }
    
    // Function to populate cities by state
    function populateCitiesByState(fieldId, stateId) {
        var $field = $('#' + fieldId);
        if (!$field.length) return;
        
        $field.empty();
        $field.append('<option value="">Select City</option>');
        
        if (stateId) {
            $.get(apiBaseUrl + '/api/v1/cities/by-state/' + stateId, function(response) {
                if (response.success && response.data) {
                    $.each(response.data, function(index, city) {
                        $field.append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                }
            });
        }
    }
    
    // Function to populate cities by country
    function populateCitiesByCountry(fieldId, countryId) {
        var $field = $('#' + fieldId);
        if (!$field.length) return;
        
        $field.empty();
        $field.append('<option value="">Select City</option>');
        
        if (countryId) {
            $.get(apiBaseUrl + '/api/v1/cities/by-country/' + countryId, function(response) {
                if (response.success && response.data) {
                    $.each(response.data, function(index, city) {
                        $field.append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                }
            });
        }
    }
    
    // Auto-populate location fields when page loads
    $(document).ready(function() {
        // Find all location fields and populate them
        $('[data-location-type="countries"]').each(function() {
            var fieldId = $(this).attr('id');
            populateCountries(fieldId);
        });
        
        $('[data-location-type="states"]').each(function() {
            var fieldId = $(this).attr('id');
            var countryField = $(this).data('parent-field');
            var countryId = $('#' + countryField).val();
            populateStatesByCountry(fieldId, countryId);
        });
        
        $('[data-location-type="cities"]').each(function() {
            var fieldId = $(this).attr('id');
            var stateField = $(this).data('parent-field');
            var stateId = $('#' + stateField).val();
            populateCitiesByState(fieldId, stateId);
        });
    });
    
    // Handle country change to update states
    $(document).on('change', '[data-location-type="countries"]', function() {
        var countryId = $(this).val();
        var countryFieldId = $(this).attr('id');
        
        // Update all state fields that depend on this country
        $('[data-location-type="states"][data-parent-field="' + countryFieldId + '"]').each(function() {
            var stateFieldId = $(this).attr('id');
            populateStatesByCountry(stateFieldId, countryId);
        });
        
        // Update all city fields that depend on this country
        $('[data-location-type="cities"][data-parent-field="' + countryFieldId + '"]').each(function() {
            var cityFieldId = $(this).attr('id');
            populateCitiesByCountry(cityFieldId, countryId);
        });
    });
    
    // Handle state change to update cities
    $(document).on('change', '[data-location-type="states"]', function() {
        var stateId = $(this).val();
        var stateFieldId = $(this).attr('id');
        
        // Update all city fields that depend on this state
        $('[data-location-type="cities"][data-parent-field="' + stateFieldId + '"]').each(function() {
            var cityFieldId = $(this).attr('id');
            populateCitiesByState(cityFieldId, stateId);
        });
    });
</script>
@endpush

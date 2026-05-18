// Location Dropdowns - Dependent dropdowns for countries, states, and cities
document.addEventListener('DOMContentLoaded', function() {
    
    // Function to populate states based on selected country
    function populateStates(countryId, stateSelect, defaultState = '') {
        if (!countryId) {
            stateSelect.innerHTML = '<option value="">Select State</option>';
            return;
        }
        
        fetch(`/admin/states/by-country/${countryId}`)
            .then(response => response.json())
            .then(states => {
                stateSelect.innerHTML = '<option value="">Select State</option>';
                states.forEach((stateName, stateId) => {
                    const option = document.createElement('option');
                    option.value = stateId;
                    option.textContent = stateName;
                    if (stateId == defaultState) {
                        option.selected = true;
                    }
                    stateSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching states:', error);
                stateSelect.innerHTML = '<option value="">Error loading states</option>';
            });
    }
    
    // Function to populate cities based on selected state
    function populateCities(stateId, citySelect, defaultCity = '') {
        if (!stateId) {
            citySelect.innerHTML = '<option value="">Select City</option>';
            return;
        }
        
        fetch(`/admin/cities/by-state/${stateId}`)
            .then(response => response.json())
            .then(cities => {
                citySelect.innerHTML = '<option value="">Select City</option>';
                cities.forEach((cityName, cityId) => {
                    const option = document.createElement('option');
                    option.value = cityId;
                    option.textContent = cityName;
                    if (cityId == defaultCity) {
                        option.selected = true;
                    }
                    citySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching cities:', error);
                citySelect.innerHTML = '<option value="">Error loading cities</option>';
            });
    }
    
    // Set up country change listeners
    const countrySelects = document.querySelectorAll('select[name="country_id"]');
    countrySelects.forEach(countrySelect => {
        countrySelect.addEventListener('change', function() {
            const stateSelect = this.closest('form').querySelector('select[name="state_id"]');
            const citySelect = this.closest('form').querySelector('select[name="city_id"]');
            
            if (stateSelect) {
                populateStates(this.value, stateSelect);
            }
            if (citySelect) {
                citySelect.innerHTML = '<option value="">Select City</option>';
            }
        });
    });
    
    // Set up state change listeners
    const stateSelects = document.querySelectorAll('select[name="state_id"]');
    stateSelects.forEach(stateSelect => {
        stateSelect.addEventListener('change', function() {
            const citySelect = this.closest('form').querySelector('select[name="city_id"]');
            
            if (citySelect) {
                populateCities(this.value, citySelect);
            }
        });
    });
    
    // Initialize dropdowns if values are pre-selected (for edit forms)
    countrySelects.forEach(countrySelect => {
        if (countrySelect.value) {
            const stateSelect = countrySelect.closest('form').querySelector('select[name="state_id"]');
            const citySelect = countrySelect.closest('form').querySelector('select[name="city_id"]');
            
            if (stateSelect && stateSelect.value) {
                populateStates(countrySelect.value, stateSelect, stateSelect.value);
                
                if (citySelect && citySelect.value) {
                    populateCities(stateSelect.value, citySelect, citySelect.value);
                }
            }
        }
    });
});

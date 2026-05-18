{{-- Example: How to use Location Fields in your Module Forms --}}

{{-- Step 1: Include the location fields component at the top of your module form --}}
@include('admin.modules.inc.location_fields')

{{-- Step 2: Create your form fields with proper data attributes --}}

{{-- Country Field --}}
<div class="form-group">
    <label for="country">Country *</label>
    <select class="form-control" id="country" name="country" data-location-type="countries" required>
        <option value="">Select Country</option>
    </select>
</div>

{{-- State Field (depends on country) --}}
<div class="form-group">
    <label for="state">State/Province *</label>
    <select class="form-control" id="state" name="state" data-location-type="states" data-parent-field="country" required>
        <option value="">Select State/Province</option>
    </select>
</div>

{{-- City Field (depends on state) --}}
<div class="form-group">
    <label for="city">City *</label>
    <select class="form-control" id="city" name="city" data-location-type="cities" data-parent-field="state" required>
        <option value="">Select City</option>
    </select>
</div>

{{-- 
EXPLANATION:

1. @include('admin.modules.inc.location_fields') - This loads all the JavaScript functions and location data

2. data-location-type="countries" - Tells the system this is a countries dropdown
3. data-location-type="states" - Tells the system this is a states dropdown  
4. data-location-type="cities" - Tells the system this is a cities dropdown

5. data-parent-field="country" - For states field, tells it to depend on the country field
6. data-parent-field="state" - For cities field, tells it to depend on the state field

The JavaScript will automatically:
- Populate countries when the page loads
- Update states when a country is selected
- Update cities when a state is selected
- Handle all the AJAX calls to your API endpoints

--}}

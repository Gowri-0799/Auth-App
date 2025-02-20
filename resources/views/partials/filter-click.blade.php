<div class="col-md-3">
            <label for="filter" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Filter</label>
            <select id="filter" name="filter" class="form-select">
                <option value="month_to_date" {{ request('filter') == 'month_to_date' ? 'selected' : '' }}>Month to Date</option>
                <option value="this_month" {{ request('filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                <option value="last_12_months" {{ request('filter') == 'last_12_months' ? 'selected' : '' }}>Last 12 Months</option>
                <option value="last_6_months" {{ request('filter') == 'last_6_months' ? 'selected' : '' }}>Last 6 Months</option>
                <option value="last_3_months" {{ request('filter') == 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                <option value="last_1_month" {{ request('filter') == 'last_1_month' ? 'selected' : '' }}>Last 1 Month</option>
                <option value="last_month" {{ request('filter') == 'last_month' ? 'selected' : '' }}>Last Month</option>
                <option value="last_7_days" {{ request('filter') == 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="custom_range" {{ request('filter') == 'custom_range' ? 'selected' : '' }}>Custom Range</option>
            </select>
        </div>
        <!-- Start Date Field -->
        <div class="col-md-3" id="startDateContainer">
            <label for="startDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">Start Date</label>
            <input type="date" id="startDate" name="startDate" class="form-control" 
                value="{{ request('startDate', '') }}" disabled>
        </div>
        <!-- End Date Field -->
        <div class="col-md-3" id="endDateContainer">
            <label for="endDate" class="form-label fw-bold" style="font-family: Arial, sans-serif; font-size: 14px;">End Date</label>
            <input type="date" id="endDate" name="endDate" class="form-control" 
                value="{{ request('endDate', '') }}" disabled>
        </div>
        <div class="col-md-1">
    <button type="submit" class="btn button-clearlink text-primary fw-bold">Submit</button>
</div>
<a href="{{ url()->current() }}" class="reset-link text-decoration-none text-primary d-inline-block" onclick="resetFilter(event)">Reset</a>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var filter = document.getElementById("filter");
        var startDate = document.getElementById("startDate");
        var endDate = document.getElementById("endDate");
        var resetLink = document.querySelector("a.reset-link"); // Corrected selector

        function toggleDateFields() {
            if (filter.value === "custom_range") {
                startDate.disabled = false;
                endDate.disabled = false;
            } else {
                startDate.disabled = true;
                endDate.disabled = true;
                startDate.value = ""; // Clear values when disabled
                endDate.value = "";
            }
        }

        function resetFilter(event) {
            event.preventDefault(); // Prevent the default link behavior
            
            // Reset the dropdown and date fields
            filter.value = "month_to_date"; // Default filter
            startDate.value = ""; // Clear Start Date
            endDate.value = ""; // Clear End Date

            // Disable the date fields as default
            startDate.disabled = true;
            endDate.disabled = true;

            // Remove query parameters from the URL
            let url = new URL(window.location.href);
            url.searchParams.delete("filter");
            url.searchParams.delete("startDate");
            url.searchParams.delete("endDate");
            url.searchParams.delete("section");

            // Redirect to the cleaned URL to refresh the data
            window.location.href = url.toString();
        }

        // Initial check in case of pre-selected value
        toggleDateFields();

        // Add event listener to filter dropdown
        filter.addEventListener("change", toggleDateFields);

        // Attach the reset function to the reset button/link
        if (resetLink) {
            resetLink.addEventListener("click", resetFilter);
        }
    });
</script>


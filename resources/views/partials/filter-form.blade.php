<div class="col-md-2">
    <label for="start_date" class="form-label fw-bold">Start Date</label>
    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
</div>
<div class="col-md-2">
    <label for="end_date" class="form-label fw-bold">End Date</label>
    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
</div>
<div class="col-md-2">
    <label for="rows_to_show" class="form-label fw-bold">Show</label>
    <select name="rows_to_show" id="rows_to_show" class="form-select">
        <option value="10" {{ request('rows_to_show') == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ request('rows_to_show') == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ request('rows_to_show') == 50 ? 'selected' : '' }}>50</option>
    </select>
</div>
<div class="col-md-3">
    <label for="search" class="form-label fw-bold">Search</label>
    <input type="text" name="search" id="search" class="form-control" placeholder="Search here..." value="{{ request('search') }}">
</div>
<div class="col-md-1">
    <button type="submit" class="btn button-clearlink text-primary fw-bold">Submit</button>
</div>
<a href="{{ url()->current() }}" class="text-decoration-none text-primary d-inline-block">Reset</a>

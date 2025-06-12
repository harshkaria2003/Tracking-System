<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.employee.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
            @error('name')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
            @error('email')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>
<div class="mb-3">
 
  </select>
  @error('project_id')
    <div class="text-danger small">{{ $message }}</div>
  @enderror
</div>



          <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required value="{{ old('start_date') }}">
            @error('start_date')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" required value="{{ old('end_date') }}">
            @error('end_date')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="country_id" class="form-label">Country</label>
            <select name="country_id" id="country_id" class="form-select" required>
              <option value="" disabled selected>Select Country</option>
              @if(isset($countries) && $countries->count())
                @foreach($countries as $country)
                  <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                    {{ $country->name }}
                  </option>
                @endforeach
              @else
                <option disabled>No countries available</option>
              @endif
            </select>
            @error('country_id')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Employee</button>
        </div>
      </div>
    </form>
  </div>
</div>

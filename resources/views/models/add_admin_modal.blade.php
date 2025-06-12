<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('superadmin.admin.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAdminModalLabel">Add Admin</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="adminFullName" class="form-label">Full Name</label>
            <input type="text" name="name" id="adminFullName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="organizationName" class="form-label">Organization Name</label>
            <input type="text" name="organization_name" id="organizationName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="projectNameAdmin" class="form-label">Project Name</label>
            <input type="text" name="project_name" id="projectNameAdmin" class="form-control" required>
          </div>
          <div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="email" class="form-control" required>
</div>

          <input type="hidden" name="role_id" value="2"> <!-- Assuming role_id 2 for Admin -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Admin</button>
        </div>
      </div>
    </form>
  </div>
</div>
  
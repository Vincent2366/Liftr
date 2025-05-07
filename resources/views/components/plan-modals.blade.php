<!-- Plan Change Modal -->
<div class="modal fade" id="planModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Confirm Plan Change</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="modalMessage">Are you sure you want to change the plan?</p>
        <p class="text-muted small" id="modalWarning"></p>
        <form id="planChangeForm" action="" method="POST">
          @csrf
          <input type="hidden" name="plan" id="selectedPlan" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="confirmButton" class="btn btn-primary" onclick="submitPlanForm()">Confirm</button>
      </div>
    </div>
  </div>
</div>

<!-- Subscription Modal -->
<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Upgrade</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Please confirm domain upgrade to Premium plan.</p>
        <form id="subscriptionForm" action="" method="POST">
          @csrf
          <input type="hidden" name="plan" value="premium">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="submitSubscriptionForm()">Confirm Upgrade</button>
      </div>
    </div>
  </div>
</div>

<script>
  function openPlanModal(plan, tenantId) {
    const selectedPlan = document.getElementById('selectedPlan');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalWarning = document.getElementById('modalWarning');
    const currentTenantIdField = document.getElementById('current-tenant-id');
    
    // Update the tenant ID for this specific operation
    if (tenantId) {
      currentTenantIdField.value = tenantId;
    }
    
    selectedPlan.value = plan;
    
    if (plan === 'free') {
      modalTitle.textContent = 'Confirm Downgrade to Free';
      modalMessage.textContent = 'Are you sure you want to downgrade to the Free plan?';
      modalWarning.textContent = 'When downgrading from Premium to Free, only the 3 oldest users will remain active.';
    }
    
    const planModal = new bootstrap.Modal(document.getElementById('planModal'));
    planModal.show();
  }
  
  function openSubscriptionModal(plan, tenantId) {
    // Update the tenant ID for this specific operation
    const currentTenantIdField = document.getElementById('current-tenant-id');
    if (tenantId) {
      currentTenantIdField.value = tenantId;
    }
    
    // Update the plan value in the form
    const planField = document.querySelector('#subscriptionForm input[name="plan"]');
    if (planField) {
      planField.value = plan;
    }
    
    const subscriptionModal = new bootstrap.Modal(document.getElementById('subscriptionModal'));
    subscriptionModal.show();
  }
  
  function submitPlanForm() {
    // Get the tenant ID from the current context
    let tenantId = document.getElementById('current-tenant-id').value;
    
    if (!tenantId) {
      console.error('Could not determine tenant ID');
      alert('Error: Could not determine tenant ID. Please try again or contact support.');
      return;
    }
    
    const formData = new FormData(document.getElementById('planChangeForm'));
    
    // Log the data being sent
    console.log('Submitting plan change for tenant:', tenantId);
    console.log('Plan:', formData.get('plan'));
    
    fetch(`/admin/tenant/${tenantId}/update-plan`, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => {
      if (!response.ok) {
        return response.json().then(data => {
          throw new Error(data.message || `HTTP error! Status: ${response.status}`);
        });
      }
      return response.json();
    })
    .then(data => {
      console.log('Plan update successful:', data);
      alert('Plan updated successfully!');
      // Force page reload to reflect changes
      window.location.reload();
    })
    .catch(error => {
      console.error('Error updating plan:', error);
      alert('Error updating plan: ' + error.message);
    });
  }
  
  function submitSubscriptionForm() {
    // Use the same tenant ID extraction logic
    let tenantId = document.getElementById('current-tenant-id').value;
    
    if (!tenantId) {
      console.error('Could not determine tenant ID');
      alert('Error: Could not determine tenant ID. Please try again or contact support.');
      return;
    }
    
    const formData = new FormData(document.getElementById('subscriptionForm'));
    
    // Log the data being sent
    console.log('Submitting subscription change for tenant:', tenantId);
    console.log('Plan:', formData.get('plan'));
    
    fetch(`/admin/tenant/${tenantId}/update-plan`, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
      }
    })
    .then(response => {
      if (!response.ok) {
        return response.json().then(data => {
          throw new Error(data.message || `HTTP error! Status: ${response.status}`);
        });
      }
      return response.json();
    })
    .then(data => {
      console.log('Subscription update successful:', data);
      alert('Subscription updated successfully!');
      // Force page reload to reflect changes
      window.location.reload();
    })
    .catch(error => {
      console.error('Error updating subscription:', error);
      alert('Error updating subscription: ' + error.message);
    });
  }
</script>








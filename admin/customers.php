<?php 


include 'includes/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Customers List</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>id</th>
                            <th>Title</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Contact No</th>
                            <th>District</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
    

                    </tbody>
                </table>

            </div>
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Add Customers</h4>
               
            </div>
            <div class="card-body">
  <div id="responseMessage" class="mt-1 text-center"></div>

                <form id="customerForm">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label for="title">Title</label>
                            <select name="title"  class="form-control">
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Miss">Miss</option>
                                <option value="Dr">Dr</option>
                                <option value="Prof">Prof</option>
                            </select>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label for="">First Name</label>
                            <input type="text" name="firstname" class="form-control">
                        </div>

                        <div class="col-md-5 mb-3">
                            <label for="">Last Name</label>
                            <input type="text" name="lastname" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="">Contact No</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="">District</label>
                            <input type="text" name="district" class="form-control">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" name="saveCustomer" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>     
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const customerForm = document.getElementById('customerForm');
    const responses = document.getElementById('responseMessage');
    const tableBody = document.querySelector('table tbody');

    // Load customers into the table
    function loadCustomers() {
        fetch('customer-fetch.php')
            .then(res => res.text())
            .then(html => {
                tableBody.innerHTML = html;

                // Add delete event listeners to new rows
                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const customerId = this.dataset.id;
                        if (confirm('Are you sure you want to delete this customer?')) {
                            deleteCustomer(customerId);
                        }
                    });
                });
            })
            .catch(err => console.error('Error fetching customers:', err));
    }

    // Delete customer via AJAX
    function deleteCustomer(id) {
        fetch('customer-delete.php?id=' + id, {
            method: 'GET'
        })
        .then(res => res.json())
        .then(data => {
            responses.innerHTML = `<div class="alert alert-${data.status === 'success' ? 'success' : 'danger'}">${data.message}</div>`;
            loadCustomers(); // Refresh table after deletion

            // Fade message
            setTimeout(() => {
                responses.innerHTML = '';
            }, 2500);
        })
        .catch(err => console.error('Error deleting customer:', err));
    }

    // Handle form submit
    customerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(customerForm);

        fetch('code.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            responses.innerHTML = `<div class="alert alert-${data.status === 'success' ? 'success' : 'danger'}">${data.message}</div>`;
            if (data.status === 'success') {
                customerForm.reset();
                loadCustomers();
            }

            // Fade message
            setTimeout(() => {
                responses.innerHTML = '';
            }, 2500);
        })
        .catch(err => console.error('Error submitting form:', err));
    });

    // Initial load
    loadCustomers();
});
</script>


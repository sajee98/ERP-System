<?php
include 'includes/header.php';
?>
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
                            <th>ID</th>
                            <th>Title</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Contact No</th>
                            <th>District</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Add Customers</h4>
            </div>
            <div class="card-body">
                <div id="responseMessage" class="mt-1 text-center"></div>
                <form id="customerForm" novalidate>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label for="title">Title</label>
                            <select name="title" class="form-control">
                                <option value="">Select</option>
                                <option value="Mr">Mr</option>
                                <option value="Mrs">Mrs</option>
                                <option value="Miss">Miss</option>
                                <option value="Dr">Dr</option>
                                <option value="Prof">Prof</option>
                            </select>
                            <div class="invalid-feedback" data-field="title"></div>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label>First Name</label>
                            <input type="text" name="firstname" class="form-control">
                            <div class="invalid-feedback" data-field="firstname"></div>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="lastname" class="form-control">
                            <div class="invalid-feedback" data-field="lastname"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Contact No</label>
                            <input type="text" name="phone" class="form-control">
                            <div class="invalid-feedback" data-field="phone"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>District</label>
                            <input type="text" name="district" class="form-control">
                            <div class="invalid-feedback" data-field="district"></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" name="saveCustomer" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerForm = document.getElementById('customerForm');
        const responses = document.getElementById('responseMessage');
        const tableBody = document.querySelector('table tbody');

        function loadCustomers() {
            fetch('customer-fetch.php')
                .then(res => res.text())
                .then(html => {
                    tableBody.innerHTML = html;
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

        function deleteCustomer(id) {
            fetch('customer-delete.php?id=' + encodeURIComponent(id), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    responses.innerHTML = `<div class="alert alert-${data.status === 'success' ? 'success' : 'danger'}">${data.message}</div>`;
                    loadCustomers();
                    setTimeout(() => {
                        responses.innerHTML = '';
                    }, 2500);
                })
                .catch(err => console.error('Error deleting customer:', err));
        }

        function clearErrors() {
            responses.innerHTML = '';
            customerForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            customerForm.querySelectorAll('.invalid-feedback').forEach(div => div.textContent = '');
        }

        function showFieldErrors(errors) {
            for (const [field, msg] of Object.entries(errors || {})) {
                const input = customerForm.querySelector(`[name="${field}"]`);
                const feedback = customerForm.querySelector(`.invalid-feedback[data-field="${field}"]`);
                if (input) input.classList.add('is-invalid');
                if (feedback) feedback.textContent = msg;
            }
        }

        function showAlert(type, text) {
            responses.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${text}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
        }

        function clientValidate() {
            const errors = {};
            const title = customerForm.title.value.trim();
            const firstname = customerForm.firstname.value.trim();
            const lastname = customerForm.lastname.value.trim();
            const phone = customerForm.phone.value.trim();
            const district = customerForm.district.value.trim();

            if (!title) errors.title = 'Title is required';
            if (!firstname) errors.firstname = 'First name is required';
            if (firstname && firstname.length < 2) errors.firstname = 'First name must be at least 2 characters';
            if (!lastname) errors.lastname = 'Last name is required';
            if (!phone) errors.phone = 'Contact number is required';
            if (phone && !/^[0-9+\-\s]{7,20}$/.test(phone)) errors.phone = 'Invalid phone number';
            if (!district) errors.district = 'District is required';
            return errors;
        }

        customerForm.addEventListener('input', function(e) {
            const name = e.target.name;
            if (!name) return;
            const errorDiv = customerForm.querySelector(`.invalid-feedback[data-field="${name}"]`);
            if (errorDiv && e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
                errorDiv.textContent = '';
            }
        });

        customerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            const preErrors = clientValidate();
            if (Object.keys(preErrors).length) {
                showFieldErrors(preErrors);
                showAlert('danger', 'Please fill the fields');
                return;
            }

            const formData = new FormData(customerForm);
            fetch('code.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.message) {
                        responses.innerHTML = `<div class="alert alert-${data.status === 'success' ? 'success' : 'danger'}">${data.message}</div>`;
                    }
                    if (data.errors) {
                        showFieldErrors(data.errors);
                    }
                    if (data.status === 'success') {
                        customerForm.reset();
                        loadCustomers();
                    }
                    setTimeout(() => {
                        responses.innerHTML = '';
                    }, 2500);
                })
                .catch(err => console.error('Error submitting form:', err));
        });

        loadCustomers();
    });
</script>
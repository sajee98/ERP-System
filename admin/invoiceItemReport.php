<?php
include 'includes/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">#INvoice Item Report</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Invoice No</th>
                            <th>Invoice Date </th>
                            <th>Customer</th>
                            <th>item name + Code</th>
                            <th>Item Category</th>
                            <th>Item Sub Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('table tbody');

        function loadCustomers() {
            fetch('invoiceItemReport-fetch.php')
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

        loadCustomers();
    });
</script>
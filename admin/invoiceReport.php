<?php
include 'includes/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Invoice Report</h4>
                <div class="d-flex gap-2 align-items-center">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">From</span>
                        <input id="fromDate" type="date" class="form-control form-control-sm">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">To</span>
                        <input id="toDate" type="date" class="form-control form-control-sm">
                    </div>
                    <input id="searchBox" class="form-control form-control-sm" style="min-width:220px" placeholder="Search invoice, customer, district...">
                    <button id="clearFilters" class="btn btn-sm btn-secondary">Clear</button>
                </div>
            </div>
            
            <div class="card-body">
                <div id="responses" class="mb-2"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Invoice No</th>
                                <th>Invoice Date</th>
                                <th>Customer</th>
                                <th>District</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('tableBody');
    const responses = document.getElementById('responses');
    const fromDate = document.getElementById('fromDate');
    const toDate = document.getElementById('toDate');
    const searchBox = document.getElementById('searchBox');
    const clearBtn = document.getElementById('clearFilters');

    function debounce(fn, delay = 300) {
        let t;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...args), delay);
        };
    }

    function showMessage(html, timeout = 3000) {
        responses.innerHTML = html;
        if (timeout > 0) setTimeout(() => responses.innerHTML = '', timeout);
    }

    function loadInvoices() {
        const params = new URLSearchParams();
        if (fromDate.value) params.append('from', fromDate.value);
        if (toDate.value) params.append('to', toDate.value);
        if (searchBox.value.trim() !== '') params.append('search', searchBox.value.trim());

        fetch('invoiceReport-fetch.php?' + params.toString())
            .then(res => res.text())
            .then(html => tableBody.innerHTML = html)
            .catch(err => showMessage('<div class="alert alert-danger">Error loading invoices</div>'));
    }

    fromDate.addEventListener('change', loadInvoices);
    toDate.addEventListener('change', loadInvoices);
    searchBox.addEventListener('input', debounce(loadInvoices));
    clearBtn.addEventListener('click', function() {
        fromDate.value = '';
        toDate.value = '';
        searchBox.value = '';
        loadInvoices();
    });

    loadInvoices();
});
</script>

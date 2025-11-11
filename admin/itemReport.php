<?php
include 'includes/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Item Report</h4>
                <div class="d-flex gap-2 align-items-center">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">From</span>
                        <input id="fromDate" type="date" class="form-control form-control-sm">
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">To</span>
                        <input id="toDate" type="date" class="form-control form-control-sm">
                    </div>
                    <input id="searchBox" class="form-control form-control-sm" style="min-width:220px" placeholder="Search item name, category, subcategory...">
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
                                <th>Item name</th>
                                <th>Item Category</th>
                                <th>Item Sub Category</th>
                                <th>Quantity</th>
                                <th>Added Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">

                        </tbody>
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

    function debounce(fn, delay = 350) {
        let t;
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn(...args), delay);
        };
    }

    function showMessage(html, timeout = 3000) {
        responses.innerHTML = html;
        if (timeout > 0) {
            setTimeout(() => responses.innerHTML = '', timeout);
        }
    }

    function loadItems() {
        const params = new URLSearchParams();
        if (fromDate.value) params.append('from', fromDate.value);
        if (toDate.value) params.append('to', toDate.value);
        if (searchBox.value && searchBox.value.trim() !== '') params.append('search', searchBox.value.trim());

        fetch('itemReport-fetch.php?' + params.toString())
            .then(res => res.text())
            .then(html => {
                tableBody.innerHTML = html;
                document.querySelectorAll('.btn-delete').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const itemID = this.dataset.id;
                        if (!itemID) return;
                        if (confirm('Are you sure you want to delete this item?')) {
                            deleteItem(itemID);
                        }
                    });
                });
            })
            .catch(err => {
                console.error('Error fetching items:', err);
                showMessage(`<div class="alert alert-danger">Error loading items</div>`);
            });
    }

function deleteItem(id) {
    fetch('itemDelete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ id: String(id) })
    })
    .then(async res => {
        // optional: check HTTP status
        if (!res.ok) {
            const text = await res.text();
            throw new Error('Server returned ' + res.status + ': ' + text);
        }
        return res.json();
    })
    .then(data => {
        showMessage(`<div class="alert alert-${data.status === 'success' ? 'success' : 'danger'}">${data.message}</div>`, 2500);
        loadItems();
    })
    .catch(err => {
        console.error('Error deleting item:', err);
        showMessage('<div class="alert alert-danger">Delete request failed — see console for details</div>');
    });
}


    fromDate.addEventListener('change', loadItems);
    toDate.addEventListener('change', loadItems);
    searchBox.addEventListener('input', debounce(loadItems));
    clearBtn.addEventListener('click', function() {
        fromDate.value = '';
        toDate.value = '';
        searchBox.value = '';
        loadItems();
    });

    loadItems();
});
</script>

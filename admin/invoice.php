<?php
include 'includes/header.php';
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Invoice Items</h4>
            </div>
            <div class="card-body">
                <div id="responseMessage" class="mt-1 text-center"></div>

                <!-- Note: form id is invoiceForm -->
                <form id="invoiceForm" novalidate>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="invoice_number" class="form-label">Invoice No</label>
                            <input type="text" name="invoice_number" id="invoice_number" class="form-control" readonly>
                            <div class="invalid-feedback" data-field="invoice_number"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control" required>
                            <div class="invalid-feedback" data-field="invoice_date"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                            <div class="invalid-feedback" data-field="customer_name"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="district" class="form-label">Customer District</label>
                            <input type="text" name="district" id="district" class="form-control" required>
                            <div class="invalid-feedback" data-field="district"></div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="item_code" class="form-label">Item Code</label>
                            <input type="text" name="item_code" id="item_code" class="form-control" required>
                            <div class="invalid-feedback" data-field="item_code"></div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="item_name" class="form-label">Item Name</label>
                            <input type="text" name="item_name" id="item_name" class="form-control" required>
                            <div class="invalid-feedback" data-field="item_name"></div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="item_category" class="form-label">Item Category</label>
                            <select name="item_category" id="item_category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Clothing">Clothing</option>
                                <option value="Furniture">Furniture</option>
                            </select>
                            <div class="invalid-feedback" data-field="item_category"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="item_sub_category" class="form-label">Item Sub Category</label>
                            <select name="item_sub_category" id="item_sub_category" class="form-control" required>
                                <option value="">Select Sub Category</option>
                            </select>
                            <div class="invalid-feedback" data-field="item_sub_category"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="0" step="1" required>
                            <div class="invalid-feedback" data-field="quantity"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="unit_price" class="form-label">Unit Price</label>
                            <input type="number" name="unit_price" id="unit_price" class="form-control" min="0" step="0.01" required>
                            <div class="invalid-feedback" data-field="unit_price"></div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="total_price" class="form-label">Total</label>
                            <input type="text" name="total" id="total_price" class="form-control" readonly value="0.00">
                            <div class="invalid-feedback" data-field="total"></div>
                        </div>

                    </div>

                    <div class="mt-3">
                        <button type="submit" id="submitBtn" name="submitBtn" class="btn btn-primary">Sell</button>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const invoiceForm = document.getElementById('invoiceForm');
        const responseMessage = document.getElementById('responseMessage');
        const submitBtn = document.getElementById('submitBtn');

        // Fields used for calculations
        const quantity = document.getElementById('quantity');
        const unitPrice = document.getElementById('unit_price');
        const totalPrice = document.getElementById('total_price');

        // Category -> subcategories map
        const categorySelect = document.getElementById('item_category');
        const subCategorySelect = document.getElementById('item_sub_category');
        const subCategories = {
            "Electronics": ["Mobile Phones", "Laptops", "Televisions", "Cameras"],
            "Clothing": ["Men's Wear", "Women's Wear", "Kids' Wear"],
            "Furniture": ["Sofas", "Tables", "Chairs", "Beds"]
        };

        // Populate subcategories when category changes
        categorySelect.addEventListener('change', () => {
            const selectedCategory = categorySelect.value;
            subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
            if (subCategories[selectedCategory]) {
                subCategories[selectedCategory].forEach(subCat => {
                    const opt = document.createElement('option');
                    opt.value = subCat;
                    opt.textContent = subCat;
                    subCategorySelect.appendChild(opt);
                });
            }
        });

        // Total calculation
        function calculateTotal() {
            const qty = parseFloat(quantity?.value) || 0;
            const price = parseFloat(unitPrice?.value) || 0;
            const total = (qty * price);
            if (totalPrice) totalPrice.value = total.toFixed(2);
        }
        if (quantity) quantity.addEventListener('input', calculateTotal);
        if (unitPrice) unitPrice.addEventListener('input', calculateTotal);

        // Helpers for showing/clearing validation UI
        function clearErrors() {
            responseMessage.innerHTML = '';
            invoiceForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            invoiceForm.querySelectorAll('.invalid-feedback').forEach(div => div.textContent = '');
        }

        function showFieldErrors(errors) {
            for (const [field, msg] of Object.entries(errors || {})) {
                const input = invoiceForm.querySelector(`[name="${field}"]`);
                const feedback = invoiceForm.querySelector(`.invalid-feedback[data-field="${field}"]`);
                if (input) input.classList.add('is-invalid');
                if (feedback) feedback.textContent = msg;
            }
        }

        function showAlert(type, text) {
            responseMessage.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${text}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
        }

      function clientValidate() {
    const errors = {};
    const itemCode = (invoiceForm.item_code?.value || '').trim();
    const itemName = (invoiceForm.item_name?.value || '').trim();
    const qtyVal = invoiceForm.quantity?.value;
    const unitVal = invoiceForm.unit_price?.value;

    if (!itemCode) errors.item_code = 'Item code is required';
    if (!itemName) errors.item_name = 'Item name is required';
    if (unitVal === '' || unitVal === null || isNaN(unitVal) || Number(unitVal) < 0)
        errors.unit_price = 'Unit price is required and must be 0 or greater';
    if (qtyVal === '' || qtyVal === null || isNaN(qtyVal) || Number(qtyVal) < 0)
        errors.quantity = 'Quantity must be 0 or greater';

    return errors;
}


        // date show

        const invoiceDateInput = document.getElementById('invoice_date');
if (invoiceDateInput) {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  invoiceDateInput.value = `${year}-${month}-${day}`; // format: YYYY-MM-DD
}
        invoiceForm.addEventListener('input', function(e) {
            const name = e.target.name;
            if (!name) return;
            const errorDiv = invoiceForm.querySelector(`.invalid-feedback[data-field="${name}"]`);
            if (errorDiv && e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
                errorDiv.textContent = '';
            }
        });

        let invoiceCounter = 1; // start from 1

        function generateInvoiceNo() {
            const today = new Date();
            const y = today.getFullYear();
            const m = String(today.getMonth() + 1).padStart(2, '0');
            const d = String(today.getDate()).padStart(2, '0');

            const invoiceNo = `INV-${y}${m}${d}-${String(invoiceCounter).padStart(4, '0')}`;
            return invoiceNo;
        }

        // Set initial invoice number on load
        const invoiceNumberInput = document.getElementById('invoice_number');
        if (invoiceNumberInput) {
            invoiceNumberInput.value = generateInvoiceNo();
        }

        // Submit handler
        invoiceForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearErrors();

            const preErrors = clientValidate();
            if (Object.keys(preErrors).length) {
                showFieldErrors(preErrors);
                showAlert('danger', 'Please fill the details.');
                return;
            }

            // disable submit while saving
            submitBtn.disabled = true;
            const originalBtnText = submitBtn.textContent;
            submitBtn.textContent = 'Saving...';

            try {
                const formData = new FormData(invoiceForm);
                // Replace 'itemInsert.php' with the correct endpoint if different
                const resp = await fetch('ivoiceCode.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                // read response as text then try to parse JSON (robust parsing)
                const text = await resp.text();
                let data = null;
                try {
                    data = JSON.parse(text);
                } catch (err) {
                    console.error('Server returned non-JSON response:', text);
                    showAlert('danger', 'Server error — unexpected response. Check console/network.');
                    return;
                }

                if (data.status === 'success') {
                    showAlert('success', data.message || 'Saved successfully.');
                    invoiceForm.reset();
                    if (totalPrice) totalPrice.value = '0.00';
                } else {
                    // server-side validation errors expected in data.errors
                    if (data.errors) {
                        showFieldErrors(data.errors);
                        showAlert('danger', data.message || 'Please correct the errors and try again.');
                    } else {
                        showAlert('danger', data.message || 'An error occurred while saving.');
                    }
                }

            } catch (err) {
                console.error('Fetch error:', err);
                showAlert('danger', 'Network or server error. Please try again.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }

            if (data.status === 'success') {
    showAlert('success', data.message || 'Saved successfully.');
    invoiceForm.reset();
    if (totalPrice) totalPrice.value = '0.00';
    
    // Generate next invoice number
    invoiceCounter++;
    invoiceNumberInput.value = generateInvoiceNo();
}

        });
    });
</script>
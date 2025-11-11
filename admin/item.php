<?php
include 'includes/header.php';
?>
<div class="container mt-4">
  <div class="card">
    <div class="card-header"><h4 class="card-title mb-0">Add Item</h4></div>
    <div class="card-body">
      <div id="responseMessage" class="mb-3"></div>

      <form id="itemForm" novalidate>
        <div class="row">

          <div class="col-md-4 mb-3">
            <label for="item_code" class="form-label">Item Code</label>
            <input type="text" name="item_code" id="item_code" class="form-control" required>
            <div class="invalid-feedback" data-field="item_code"></div>
          </div>

          <div class="col-md-4 mb-3">
            <label for="item_name" class="form-label">Item Name</label>
            <input type="text" name="item_name" id="item_name" class="form-control" required>
            <div class="invalid-feedback" data-field="item_name"></div>
          </div>

          <div class="col-md-4 mb-3">
            <label for="item_category" class="form-label">Item Category</label>
            <select name="item_category" id="item_category" class="form-control" required>
              <option value="">Select Category</option>
              <option value="Electronics">Electronics</option>
              <option value="Clothing">Clothing</option>
              <option value="Furniture">Furniture</option>
            </select>
            <div class="invalid-feedback" data-field="item_category"></div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="item_sub_category" class="form-label">Item Sub Category</label>
            <select name="item_sub_category" id="item_sub_category" class="form-control" required>
              <option value="">Select Sub Category</option>
            </select>
            <div class="invalid-feedback" data-field="item_sub_category"></div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="0" required>
            <div class="invalid-feedback" data-field="quantity"></div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="unit_price" class="form-label">Unit Price</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" min="0" step="0.01" required>
            <div class="invalid-feedback" data-field="unit_price"></div>
          </div>

          <div class="col-md-6 mb-3">
            <label for="total_price" class="form-label">Total Price</label>
            <!-- NAME must be total_price so server receives it -->
            <input type="number" name="total_price" id="total_price" class="form-control" min="0" step="0.01" readonly>
            <div class="invalid-feedback" data-field="total_price"></div>
          </div>

        </div>

        <div class="mt-3">
          <button type="submit" id="submitBtn" class="btn btn-primary">Save Item</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const categorySelect = document.getElementById("item_category");
  const subCategorySelect = document.getElementById("item_sub_category");

  const itemForm = document.getElementById('itemForm');
  const responseMessage = document.getElementById('responseMessage');
  const submitBtn = document.getElementById('submitBtn');

  const quantity = document.getElementById("quantity");
  const unitPrice = document.getElementById("unit_price");
  const totalPrice = document.getElementById("total_price");

  function calculateTotal() {
    const qty = parseFloat(quantity.value) || 0;
    const price = parseFloat(unitPrice.value) || 0;
    totalPrice.value = (qty * price).toFixed(2);
  }
  quantity.addEventListener("input", calculateTotal);
  unitPrice.addEventListener("input", calculateTotal);

  const subCategories = {
    Electronics: ["Mobile Phones", "Laptops", "Televisions", "Cameras"],
    Clothing: ["Men's Wear", "Women's Wear", "Kids' Wear"],
    Furniture: ["Sofas", "Tables", "Chairs", "Beds"]
  };
  categorySelect.addEventListener("change", function() {
    const selectedCategory = this.value;
    subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
    if (subCategories[selectedCategory]) {
      subCategories[selectedCategory].forEach(subCat => {
        const option = document.createElement("option");
        option.value = subCat;
        option.textContent = subCat;
        subCategorySelect.appendChild(option);
      });
    }
  });

  function clearErrors() {
    responseMessage.innerHTML = '';
    itemForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    itemForm.querySelectorAll('.invalid-feedback').forEach(div => div.textContent = '');
  }

  function showFieldErrors(errors) {
    for (const [field, msg] of Object.entries(errors || {})) {
      const input = itemForm.querySelector(`[name="${field}"]`);
      const feedback = itemForm.querySelector(`.invalid-feedback[data-field="${field}"]`);
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
    const itemCode = itemForm.item_code.value.trim();
    const name = itemForm.item_name.value.trim();
    const qty = itemForm.quantity.value;
    const uPrice = itemForm.unit_price.value

    if (!itemCode) errors.item_code = 'Item code is required';
    if (!name) errors.item_name = 'Item name is required';
    if (!uPrice) errors.unit_price = 'Item proice is required';
    if (qty === '' || isNaN(qty) || Number(qty) < 0) errors.quantity = 'Quantity must be 0 or greater';
    return errors;
  }

    itemForm.addEventListener('input', function(e) {
            const name = e.target.name;
            if (!name) return;
            const errorDiv = itemForm.querySelector(`.invalid-feedback[data-field="${name}"]`);
            if (errorDiv && e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
                errorDiv.textContent = '';
            }
        });

  itemForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();

    const preErrors = clientValidate();
    if (Object.keys(preErrors).length) {
      showFieldErrors(preErrors);
      showAlert('danger', 'Please fill the fields.');
      return;
    }

    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';

    try {
      const formData = new FormData(itemForm);
      const resp = await fetch('itemInsert.php', { method: 'POST', body: formData, credentials: 'same-origin' });

      const text = await resp.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch (err) {
        console.error('Server did not return JSON. Response text:', text);
        showAlert('danger', 'Server error — check console/network response.');
        return;
      }

      if (data.status === 'success') {
        showAlert('success', data.message || 'Item saved successfully.');
        itemForm.reset();
        totalPrice.value = '0.00';
      } else {
        if (data.errors) {
          showFieldErrors(data.errors);
          showAlert('danger', data.message || 'Please correct the errors.');
        } else {
          showAlert('danger', data.message || 'An error occurred while saving the item.');
        }
      }

    } catch (err) {
      console.error('Fetch error:', err);
      showAlert('danger', 'Network or server error. Please try again.');
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Save Item';
    }
  });
});
</script>

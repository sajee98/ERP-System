<?php 
include './includes/header.php'; 
?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0"><?= isset($_GET['id']) ? 'Edit Item' : 'Add item'; ?></h4>
        <a href="item.php" class="btn btn-danger">Back</a>
      </div>

      <div class="card-body">
        <div id="responseMessage" class="mt-3 text-center"></div>

        <?php 
        // Validate the 'id' parameter
        $paramResult = checkParamId('id');
        $item = null;

        if (isset($_GET['id'])) {
            if (!is_numeric($paramResult)) {
                echo '<h2 class="text-danger">' . htmlspecialchars($paramResult) . '</h2>';
                exit;
            }

            $itemResult = getById('items', $paramResult);

            if ($itemResult['status'] == 200) {
                $item = $itemResult['data']; // Simplify access
            } else {
                echo '<h2 class="text-danger">' . htmlspecialchars($itemResult['message']) . '</h2>';
                exit;
            }
        }

        $selectedTitle = $item['titles'] ?? '';
        $itemCode = $item['item_code'] ?? '';
        $itemName = $item['item_name'] ?? '';
        $itemCat = $item['item_category'] ?? '';
        $itemSubCat = $item['item_sub_category'] ?? '';
        $qty = $item['quantity'] ?? '';
        $uPrice = $item['unit_price'] ?? '';
        $tPrice = $item['total_price'] ?? '';
        ?>

        <form action="item-update.php" id="itemForm" method="post">
          <?php if(isset($item)): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($paramResult, ENT_QUOTES) ?>">
          <?php endif; ?>

          <div class="row">
            <div class="col-md-5 mb-3">
              <label for="item_code">Item Code</label>
              <input id="item_code" type="text" name="item_code" class="form-control" value="<?= htmlspecialchars($itemCode, ENT_QUOTES) ?>">
            </div>

            <div class="col-md-5 mb-3">
              <label for="item_name">Item Name</label>
              <input id="item_name" type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($itemName, ENT_QUOTES) ?>">
            </div>

            <div class="col-md-6 mb-3">
              <label for="item_category">Item category</label>
              <input id="item_category" type="text" name="item_category" readonly class="form-control" value="<?= htmlspecialchars($itemCat, ENT_QUOTES) ?>">
            </div>

            <div class="col-md-6 mb-3">
              <label for="item_sub_category">Sub Category</label>
              <input id="item_sub_category" type="text" name="item_sub_category" readonly class="form-control" value="<?= htmlspecialchars($itemSubCat, ENT_QUOTES) ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="quantity">Quantity</label>
              <input id="quantity" type="text" name="quantity" class="form-control" value="<?= htmlspecialchars($qty, ENT_QUOTES) ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="unit_price">Unit Price</label>
              <input id="unit_price" type="text" name="unit_price" class="form-control" value="<?= htmlspecialchars($uPrice, ENT_QUOTES) ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label for="total_price">Total</label>
              <input id="total_price" type="text" name="total_price" class="form-control" readonly value="<?= htmlspecialchars($tPrice, ENT_QUOTES) ?>">
            </div>
          </div>

          <div class="mt-3">
          <button type="submit" name="updateItem" class="btn btn-primary">
    Update
</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
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
</script>
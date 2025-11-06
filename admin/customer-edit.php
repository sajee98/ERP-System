<?php 
include 'includes/header.php'; 
?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0"><?= isset($_GET['id']) ? 'Edit Customer' : 'Add Customer'; ?></h4>
        <a href="customers.php" class="btn btn-danger">Back</a>
      </div>

      <div class="card-body">
        <div id="responseMessage" class="mt-3 text-center"></div>

        <?php 
        // Validate the 'id' parameter
        $paramResult = checkParamId('id');
        $customer = null;

        if (isset($_GET['id'])) {
            if (!is_numeric($paramResult)) {
                echo '<h2 class="text-danger">' . htmlspecialchars($paramResult) . '</h2>';
                exit;
            }

            $customerResult = getById('customers', $paramResult);

            if ($customerResult['status'] == 200) {
                $customer = $customerResult['data']; // Simplify access
            } else {
                echo '<h2 class="text-danger">' . htmlspecialchars($customerResult['message']) . '</h2>';
                exit;
            }
        }

        $selectedTitle = $customer['title'] ?? '';
        $firstname = $customer['firstname'] ?? '';
        $lastname = $customer['lastname'] ?? '';
        $phoneNo = $customer['phoneNo'] ?? '';
        $district = $customer['district'] ?? '';
        ?>

        <form action="codeUpdate.php" id="customerForm" method="post">
          <?php if(isset($customer)): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($paramResult, ENT_QUOTES) ?>">
          <?php endif; ?>

          <div class="row">
            <div class="col-md-2 mb-3">
              <label for="title">Title</label>
              <select id="title" name="title" class="form-control">
                <?php
                $titles = ['Mr','Mrs','Miss','Dr','Prof'];
                foreach ($titles as $t) {
                    $sel = ($t === $selectedTitle) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($t, ENT_QUOTES) . "\" $sel>$t</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-md-5 mb-3">
              <label for="firstname">First Name</label>
              <input id="firstname" type="text" name="firstname" class="form-control" value="<?= htmlspecialchars($firstname, ENT_QUOTES) ?>">
            </div>

            <div class="col-md-5 mb-3">
              <label for="lastname">Last Name</label>
              <input id="lastname" type="text" name="lastname" class="form-control" value="<?= htmlspecialchars($lastname, ENT_QUOTES) ?>">
            </div>

            <div class="col-md-6 mb-3">
              <label for="phone">Contact No</label>
              <input id="phone" type="text" name="phone" class="form-control" value="<?= htmlspecialchars($phoneNo, ENT_QUOTES) ?>">
            </div>

            <div class="col-md-6 mb-3">
              <label for="district">District</label>
              <input id="district" type="text" name="district" class="form-control" value="<?= htmlspecialchars($district, ENT_QUOTES) ?>">
            </div>
          </div>

          <div class="mt-3">
          <button type="submit" name="UpdateCustomer" class="btn btn-primary">
    Update
</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>


<?php
include 'includes/header.php';

// Total Sales 
$queryTotal = "SELECT SUM(total) AS total_sales FROM invoices";
$resultTotal = mysqli_query($conn, $queryTotal);

$totalSales = 0;
if ($resultTotal && $rowTotal = mysqli_fetch_assoc($resultTotal)) {
    $totalSales = $rowTotal['total_sales'] ?? 0;
}

// Today Sales only
$queryToday = "SELECT SUM(total) AS today_sales FROM invoices WHERE DATE(invoice_date) = CURDATE()";
$resultToday = mysqli_query($conn, $queryToday);

$todaySales = 0;
if ($resultToday && $rowToday = mysqli_fetch_assoc($resultToday)) {
    $todaySales = $rowToday['today_sales'] ?? 0;
}
?>

<div class="col-lg-6 col-12">
  <div class="row">
    <div class="col-lg-6 col-md-4 col-12">
      <div class="card">
        <span class="mask bg-primary opacity-10 border-radius-lg"></span>
        <div class="card-body p-3 position-relative">
          <div class="row">
            <div class="col-8 text-start">
              <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                <i class="ni ni-circle-08 text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
              </div>
              <h5 class="text-white font-weight-bolder mb-0 mt-3">
                <?= number_format($totalSales, 2) ?>
              </h5>
              <span class="text-white text-sm">Total Sales</span>
            </div>
            <div class="col-4">
              <p class="text-white text-sm text-end font-weight-bolder mt-auto mb-0">+55%</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-md-6 col-12 mt-4 mt-md-0">
      <div class="card">
        <span class="mask bg-dark opacity-10 border-radius-lg"></span>
        <div class="card-body p-3 position-relative">
          <div class="row">
            <div class="col-8 text-start">
              <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                <i class="ni ni-active-40 text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
              </div>
              <h5 class="text-white font-weight-bolder mb-0 mt-3">
                <?= number_format($todaySales, 2) ?>
              </h5>
              <span class="text-white text-sm">Today Sales</span>
            </div>
            <div class="col-4">
              <p class="text-white text-sm text-end font-weight-bolder mt-auto mb-0">+124%</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>

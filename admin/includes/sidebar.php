 <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="index.php" >
        <img src="https://static.vecteezy.com/system/resources/previews/019/879/186/non_2x/user-icon-on-transparent-background-free-png.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">ERP System</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link  active" href="index.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-home text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="customers.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                             <i class="fa fa-user text-dark text-lg"></i>

            </div>
            <span class="nav-link-text ms-1">Customer</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="item.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
           <i class="fa fa-warehouse text-dark text-lg"></i>
            </div>
            <span class="nav-link-text ms-1">Items</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="invoice.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-file-invoice-dollar text-dark text-lg" ></i>
            </div>
            <span class="nav-link-text ms-1">Invoice</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="invoiceReport.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa fa-file-invoice text-dark text-lg"></i>

            </div>
            
            <span class="nav-link-text ms-1">Invoice Report</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="invoiceItemReport.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-file-invoice-dollar text-dark text-lg"></i>

            </div>
            <span class="nav-link-text ms-1">Invoice Item Report</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link  " href="itemReport.php">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
           <i class="fa fa-file-invoice-dollar text-dark text-lg"></i>

            </div>
            <span class="nav-link-text ms-1">Item Report</span>
          </a>
        </li>
      </ul>
    </div>

  </aside>

  <script>
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname.split('/').pop(); 
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.classList.remove('active');

        const linkPath = link.getAttribute('href').split('/').pop();

        if (linkPath === currentPath) {
            link.classList.add('active');

            const iconDiv = link.querySelector('.icon');
            if (iconDiv) {
                iconDiv.classList.add('bg-gradient-primary');
                iconDiv.classList.remove('bg-white');
            }
        }
    });
});
</script>

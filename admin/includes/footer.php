 <footer class="footer pt-3  ">
   <div class="container-fluid">
     <div class="row align-items-center justify-content-lg-between">
       <div class="col-lg-6 mb-lg-0 mb-4">
         <div class="copyright text-center text-sm text-muted text-lg-start">
           © <script>
             document.write(new Date().getFullYear())
           </script>,
           made with <i class="fa fa-heart"></i> by
           <a href="https://github.com/sajee98" class="font-weight-bold" target="_blank">Star Sajee</a>
           for a better web.
         </div>
       </div>
       <div class="col-lg-6">
         <ul class="nav nav-footer justify-content-center justify-content-lg-end">
           <li class="nav-item">
             <a href="/" class="nav-link text-muted" target="_blank">Contact</a>
           </li>
           <li class="nav-item">
             <a href="/" class="nav-link text-muted" target="_blank">About Us</a>
           </li>

           <li class="nav-item">
             <a href="/" class="nav-link pe-0 text-muted" target="_blank">License</a>
           </li>
         </ul>
       </div>
     </div>
   </div>
 </footer>
 </div>
 </main>

 <!--   Core JS Files   -->
 <script src="assets/js/core/popper.min.js"></script>
 <script src="assets/js/core/bootstrap.min.js"></script>
 <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
 <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
 <script src="assets/js/plugins/chartjs.min.js"></script>

 <script>
   var win = navigator.platform.indexOf('Win') > -1;
   if (win && document.querySelector('#sidenav-scrollbar')) {
     var options = {
       damping: '0.5'
     }
     Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
   }
 </script>


 <script>
   document.addEventListener('DOMContentLoaded', function() {
     const breadcrumbLast = document.querySelector('.breadcrumb .breadcrumb-item.active');
     const pageTitle = document.querySelector('nav h6.font-weight-bolder');

     const fullPath = window.location.pathname.split('/').pop() || 'index.php';
     const currentFile = fullPath.split('?')[0];

     function basenameNoExt(filename) {
       const name = filename.split('.')[0];
       return name.split('-')[0];
     }

     const currentBase = basenameNoExt(currentFile);

     const navLinks = document.querySelectorAll('.sidenav .nav-link');

     let matchedText = null;

     navLinks.forEach(link => {
       const href = link.getAttribute('href') || '';
       const hrefFile = href.split('/').pop() || '';
       const linkBase = basenameNoExt(hrefFile);

       if (hrefFile === currentFile || linkBase === currentBase) {
         matchedText = link.querySelector('.nav-link-text')?.innerText?.trim() || link.innerText.trim();
         document.querySelectorAll('.sidenav .nav-link.active').forEach(a => a.classList.remove('active'));
         link.classList.add('active');

         const iconDiv = link.querySelector('.icon');
         if (iconDiv) {
           document.querySelectorAll('.sidenav .icon').forEach(i => {
             i.classList.remove('bg-gradient-primary');
             i.classList.add('bg-white');
           });
           iconDiv.classList.remove('bg-white');
           iconDiv.classList.add('bg-gradient-primary');
         }
       }

       link.addEventListener('click', function() {
         const text = link.querySelector('.nav-link-text')?.innerText?.trim() || link.innerText.trim();
         if (breadcrumbLast) breadcrumbLast.innerText = text;
         if (pageTitle) pageTitle.innerText = text;
       });
     });

     if (matchedText) {
       if (breadcrumbLast) breadcrumbLast.innerText = matchedText;
       if (pageTitle) pageTitle.innerText = matchedText;
     } else {
       const fallback = currentBase.replace(/[-_]/g, ' ');
       const cap = fallback.charAt(0).toUpperCase() + fallback.slice(1);
       if (breadcrumbLast) breadcrumbLast.innerText = cap;
       if (pageTitle) pageTitle.innerText = cap;
     }
   });
 </script>

 <script async defer src="https://buttons.github.io/buttons.js"></script>
 <script src="assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>
 </body>

 </html>
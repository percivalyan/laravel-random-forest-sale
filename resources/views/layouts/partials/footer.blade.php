       <footer class="py-4 bg-light mt-auto">
           <div class="container-fluid px-4">
               <div class="d-flex flex-column flex-md-row align-items-center justify-content-between small">
                   <div class="text-muted">
                       &copy; <span id="currentYear"></span>
                       {{-- <a href="https://github.com/percivalyan" target="_blank" rel="noopener noreferrer">Percivalyan</a> --}}
                   </div>
                   {{-- <div class="mt-2 mt-md-0">
                       <a href="#">Privacy Policy</a>
                       &middot;
                       <a href="#">Terms & Conditions</a>
                   </div> --}}
               </div>
           </div>
       </footer>

       <script>
           document.getElementById("currentYear").textContent = new Date().getFullYear();
       </script>

       </div> {{-- end of layoutSidenav_content --}}
       </div> {{-- end of layoutSidenav --}}

<footer class="bg-dark text-white mt-5">

<div class="container py-4">

<div class="row">

<div class="col-md-4">

<h4>Taste Haven</h4>

<p>
Fresh Meals Every Day
</p>

</div>

<div class="col-md-4">

<h5>Contact</h5>

<p>Email: info@tastehaven.com</p>

<p>Phone: +250 700000000</p>

</div>

<div class="col-md-4">

<h5>Follow Us</h5>

<i class="fab fa-facebook fa-2x me-2"></i>

<i class="fab fa-instagram fa-2x me-2"></i>

<i class="fab fa-twitter fa-2x"></i>

</div>

</div>

<hr>

<p class="text-center">

© <?php echo date("Y"); ?>

Taste Haven Restaurant.

All Rights Reserved.

</p>

</div>

</footer>

<!-- Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ===========================
     Progressive Web App (PWA)
=========================== -->

<script>

if ('serviceWorker' in navigator) {

window.addEventListener('load', function () {

navigator.serviceWorker.register('/service-worker.js')

.then(function(registration){

console.log("Service Worker Registered Successfully");

})

.catch(function(error){

console.log("Service Worker Registration Failed:", error);

});

});

}

let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e)=>{

e.preventDefault();

deferredPrompt = e;

console.log("PWA Install Prompt Ready");

});

</script>
<!DOCTYPE html>
   <head>
      <meta charset="UTF-8">
      <title>Bienvenido a Pirateca</title>
      <link rel="stylesheet" href="/css/carousel.css">
      <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.15/dist/tailwind.min.css" rel="stylesheet">
   </head>
      <!-- header -->
      <?php require('partials/nav.php')?>
        <!-- end header -->

        <?php 
          #dd($user_movies);
         ?>
   
      <!-- banner -->
   <main>
      <div>
      <!-- Your content -->
      <!-- "?=" is the same as "echo $variable" -->
         <div class="flex justify-left mt-5 mx-5">
            <h1 class="text-3xl font-bold pb-5 inline">Recomendado para</h1>
            <h1 class="inline text-3xl pl-5 italic">ti</h1>
         </div>

         <!-- esto se arregla en postproduccion -->
         <div class="flex items-center h-auto slide-container">

            <?php foreach ($user_movies as $movie): ?>
            <div class="flex justify-center h-screen min-w-full w-full snap-center relative my-5 slide">
               <img src="<?= $movie->get_poster_path() ? "https://image.tmdb.org/t/p/w500".$movie->get_poster_path() : '/views/images/PLACEHOLDER.png' ?>" alt="poster" class="h-full w-4/6 rounded-2xl hover:drop-shadow-md lg:w-1/4">
               <a href="/movie/<?= $movie->get_id()?>" class="absolute bottom-0 h-10 w-1/2 bg-slate-900 border-t-2 border-x-2 border-zinc-300 rounded text-white text-center lg:w-1/4">
                  <?= $movie->get_title() ?>
               </a>
               </div>
            <?php endforeach; ?>
               
            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>
         </div>
         
      </div>
      </main>
      <!-- end banner -->


      <!--  footer -->
     <?php require('partials/footer.php') ?>
      <!-- end footer -->

      <!-- sidebar -->
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>

      <script>
         let slideIndex = 1;
            showSlides(slideIndex);

         function plusSlides(n) {
            showSlides(slideIndex += n);
         }

         function currentSlide(n) {
            showSlides(slideIndex = n);
         }

         function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            if (n > slides.length) {slideIndex = 1}    
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
               slides[i].style.display = "none";  
            }
            for (i = 0; i < dots.length; i++) {
               dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.display = "block";  
            dots[slideIndex-1].className += " active";
         }
      </script>
   </body>
</html>
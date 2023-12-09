<!DOCTYPE html>
<html>

   <!-- header -->
   <?php require('partials/nav.php')?>
   <!-- end header -->
   <body>
      <div>
       <!-- Your content -->
       <!-- "?=" is the same as "echo $variable" -->
         <div class="flex justify-left mt-5 mx-5">
            <h1 class="text-3xl font-bold pb-5 inline">Recomendado para</h1>
            <h1 class="inline text-3xl pl-5 italic">ti</h1>
         </div>
         <section aria-label="Cartelera">
            <div class="carousel" data-carousel>
               
               <button class="carousel-btn prev" data-carousel-button="prev">&#8656;</button>
               <button class="carousel-btn next" data-carousel-button="next">&#8658;</button>
               
               <ul data-slides align="center">
               <?php foreach ($user_movies as $movie): ?>
                  <li class="slide" data-active> 
                     <img src="<?= $movie->get_poster_path() ? "https://image.tmdb.org/t/p/w500".$movie->get_poster_path() : '/views/images/PLACEHOLDER.png' ?>" alt="poster" class="h-full w-4/6 rounded-2xl hover:drop-shadow-md lg:w-1/4">
                     <a href="/movie/<?= $movie->get_id()?>" class="absolute bottom-0 h-10 w-1/2 bg-slate-900 border-t-2 border-x-2 border-zinc-300 rounded text-white text-center lg:w-1/4">
                        <?= $movie->get_original_title() ?>
                     </a>
                  </li>
               </ul>
               <?php endforeach; ?>
            </div> 
         </section>    
      </div>
   </body>
   <!--  footer -->
   <?php require('partials/footer.php') ?>
   <!-- end footer -->
   <script>
       const buttons = document.querySelectorAll("[data-carousel-button]")
         
      buttons.forEach(button => {
      button.addEventListener("click", () => {
         const offset = button.dataset.carouselButton === "next" ? 1 : -1
         const slides = button
            .closest("[data-carousel]")
            .querySelector("[data-slides]")

         const activeSlide = slides.querySelector("[data-active]")
         let newIndex = [...slides.children].indexOf(activeSlide) + offset
         if (newIndex < 0) newIndex = slides.children.length - 1
         if (newIndex >= slides.children.length) newIndex = 0

         slides.children[newIndex].dataset.active = true
         delete activeSlide.dataset.active
      })
      })
   </script>
</html>
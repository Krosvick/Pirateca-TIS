<!DOCTYPE html>
<html lang="en">
   <head>
      <link rel="stylesheet" href="carousel.css">
   </head>
      <!-- header -->
      <?php require('partials/nav.php')?>
        <!-- end header -->
   
      <!-- banner -->
      <main>
         <div>
         <!-- Your content -->
         <!-- "?=" is the same as "echo $variable" -->
            <div class="flex justify-left mt-5 mx-5">
               <h1 class="text-3xl font-bold pb-5 inline">Recomendado para</h1>
               <h1 class="inline text-3xl pl-5 italic">ti</h1>
            </div>

            <div class="pic-ctn">
               <img src="https://picsum.photos/200/300?t=1" alt="" class="pic">
               <img src="https://picsum.photos/200/300?t=2" alt="" class="pic">
               <img src="https://picsum.photos/200/300?t=3" alt="" class="pic">
               <img src="https://picsum.photos/200/300?t=4" alt="" class="pic">
               <img src="https://picsum.photos/200/300?t=5" alt="" class="pic">
            </div>

            <div class="flex flex-row items-center h-auto snap-x snap-mandatory overflow-x-auto">
               <?php foreach ($user_movies as $result): ?>
               <div class="flex justify-center h-screen min-w-full w-full snap-center relative my-5">
                  <img src="<?= $result["poster_path"] ? "https://image.tmdb.org/t/p/w500".$result['poster_path'] : '/views/images/PLACEHOLDER.png' ?>" alt="poster" class="h-full w-4/6 rounded-2xl hover:drop-shadow-md lg:w-1/4">
                  <a href="/movie?id=<?= $result['id']?>" class="absolute bottom-0 h-10 w-1/2 bg-slate-900 border-t-2 border-x-2 border-zinc-300 rounded text-white text-center lg:w-1/4">
                     <?= $result['original_title'] ?>
                  </a>
               </div>
               <?php endforeach; ?>
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
   </body>
</html>
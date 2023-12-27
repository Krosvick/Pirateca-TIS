   
   <div>
      <div class="flex justify-left mt-5 mx-5">
         <h1 class="text-3xl font-bold pb-5 inline">Recomendado para</h1>
         <h1 class="inline text-3xl pl-5 italic">ti</h1>
      </div>

      
      
      <div class="carousel w-full">
         <?php $i = 1;
         foreach ($user_movies as $movie): ?>
         <div id="<?= "slide". $i?>" class="carousel-item relative w-full justify-center">   
            <a href="/movie/<?= $movie->get_id() ?>">   
               <img src="<?= $movie->get_poster_path() ? "https://image.tmdb.org/t/p/w500" . $movie->get_poster_path() : '/views/images/PLACEHOLDER.png' ?>" class="w-11/15" />
            </a> 
            <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
               <?php if($i == 1): ?>
                  <?php echo '<a href="#slide10" class="btn btn-circle">❮</a>'; 
                        echo '<a href="#slide2" class="btn btn-circle">❯</a>'; ?>
               <?php elseif($i == 10): ?>
                  <?php echo '<a href="#slide9" class="btn btn-circle">❮</a>'; 
                        echo '<a href="#slide1" class="btn btn-circle">❯</a>'; ?>
               <?php else:
                  echo '<a href="#slide'. $i - 1 .'" class="btn btn-circle">❮</a>';
                  echo '<a href="#slide'. $i + 1 .'" class="btn btn-circle">❯</a>'; ?>
               <?php endif; ?>
            </div>
         </div> 
         <?php $i++; endforeach; ?>
      </div>

   </div>

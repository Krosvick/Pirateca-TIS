<div class="drawer">
  <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
  <div class="drawer-content flex flex-col">
    <!-- Navbar -->
    <div class="w-full navbar mx-auto py-5 shadow-lg bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-80 sticky top-0 z-50 shadow-xl shadow-indigo-500/10 flex justify-between">
      <div class="flex-none lg:hidden">
        <label for="my-drawer-3" aria-label="open sidebar" class="btn btn-square btn-ghost">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </label>
      </div>
      <a href="/" class="px-2 mx-2 w-fit"><img src="/images/logo.png" alt="Logo" class="h-8 scale-150"></a>
      <div class="flex-none lg:block">
        <ul class="menu menu-horizontal">
          <!-- Navbar menu content here -->
          <li class="hidden sm:block"><a href="/">Home</a></li>
          <?php

          use Core\Application;
          ?>
          <?php if(Application::isGuest()): ?>
          <li class="hidden sm:block"><a href="/login">Login</a></li>
          <?php endif; ?>
          <?php if(!Application::isGuest()): ?>
          <li class="hidden sm:block"><a href="/profile/<?=Application::$app->user->get_id()?>/likedpost">Liked</a></li>
          <?php endif; ?>
          <li class="hidden sm:block"><a href="/information">About</a></li>
          <?php if(Application::isAdmin()): ?>
          <li class="hidden sm:block"><a href="/addMovie">Add Movie</a></li>
          <?php endif; ?>
          <form action="search" method="post">
            <li class="form-control text-white bg-white rounded-3xl w-full lg:w-fit">
              <input type="text" placeholder="Busqueda" name="busqueda" class="input input-bordered input-primary md:w-auto text-black" />
            </li>
          </form>
          <div class="dropdown dropdown-end block hidden sm:block">
            <?php if(!Application::isGuest()): ?>
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
              <div class="w-10 rounded-full">
                <img alt="Tailwind CSS Navbar component" src="https://img.icons8.com/nolan/64/user-default.png" />
              </div>
            </div>
            <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content content-center bg-gray-900 rounded-box w-52">

              <li class="hidden sm:block px mx" >
                <a href="/profile/<?=Application::get_user_id();?>" class="justify-between">
                  Profile
                </a>
              </li>
              <li class="hidden sm:block px mx">Followers <?=Application::$app->user->get_follower_count() ?> </li>
              <li class="hidden sm:block px mx">Following <?=Application::$app->user->get_following_count() ?> </li>
              <li class="hidden sm:block px mx">
                <a href="/logout">Logout</a>
              </li>

            </ul>
            <?php endif; ?>
          </div>
        </ul>
      </div>
    </div>
    <!-- Page content here -->
      <?php if (Application::$app->session->getFlash('success')): ?>
        <div class="alert alert-success">
            <p><?php echo Application::$app->session->getFlash('success') ?></p>
        </div>
      <?php endif; ?>
      <?php if (Application::$app->session->getFlash('error')): ?>
        <div class="alert alert-error">
            <p><?php echo Application::$app->session->getFlash('error') ?></p>
        </div>
      <?php endif; ?>
      <?= $content ?? '' ?>
      <?php require('footer.php'); ?>
  </div>
  <div class="drawer-side">
    <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
    <ul class="menu flex pt-40 p-4 w-80 min-h-full bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-10 bg-gray-900">
      <!-- Sidebar content here -->
      <div class="dropdown dropdown-end p-3">
        <?php if(!Application::isGuest()): ?>
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
          <div class="w-10 rounded-full">
            <img alt="Tailwind CSS Navbar component" src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
          </div>
        </div>
        <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content rounded-box w-52">
          <li>
            <a class="justify-between" href="/profile/<?= Application::get_user_id();?>">
              Profile
            </a>
          </li>
          <li><p class="justify-between">Followers <?=Application::$app->user->get_follower_count() ?> </p></li>
          <li><p>Following <?=Application::$app->user->get_following_count()?> </p></li>
          <li><a href="/logout">Logout</a></li>
        </ul>
      </div>
      <?php endif; ?>
        <li><a class="p-5" href="/">Home</a></li>
        <?php if(Application::isGuest()): ?>
        <li><a class="p-5" href="/login">Login</a></li>
        <?php endif; ?>
        <li class=""><a href="/information">About</a></li>
        <li class=""><a href="/profile/<?= Application::$app->user->get_id()?>/likedpost">Liked</a></li>
    </ul>
  </div>
</div>
<script>
document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();
    var input = document.querySelector('input[name="busqueda"]').value;
    window.location.href = '/search/' + encodeURIComponent(input);
});
</script>
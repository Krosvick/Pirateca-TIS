    <div class="drawer">
      <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
      <div class="drawer-content flex flex-col">
        <!-- Navbar -->
        <div class="w-full navbar mx-auto py-5 shadow-lg bg-clip-padding backdrop-filter backdrop-blur-sm bg-opacity-80 sticky top-0 z-50 shadow-xl shadow-indigo-500/10">
          <div class="flex-none lg:hidden">
            <label for="my-drawer-3" aria-label="open sidebar" class="btn btn-square btn-ghost">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </label>
          </div>
          <a href="/" class="flex-1 px-2 mx-2"><img src="/images/logo.png" alt="Logo" class="h-8"></a>
          <div class="flex-none lg:block">
            <ul class="menu menu-horizontal">
              <!-- Navbar menu content here -->
              <li class="hidden sm:block"><a href="/">Home</a></li>
              <li class="hidden sm:block"><a>Login</a></li>
              <li class="hidden sm:block"><a>Liked</a></li>
              <li class="form-control text-white bg-white rounded-3xl w-full lg:w-fit">
                <input type="text" placeholder="Search" class="input input-bordered input-primary md:w-auto text-black" />
              </li>
              <div class="dropdown dropdown-end block hidden sm:block">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                  <div class="w-10 rounded-full">
                    <img alt="Tailwind CSS Navbar component" src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
                  </div>
                </div>
                <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-gray-900 rounded-box w-52">
                  <li>
                    <a class="justify-between">
                      Profile
                      <span class="badge">New</span>
                    </a>
                  </li>
                  <li class="hidden sm:block">Followers <?php ?> </li>
                  <li class="hidden sm:block">Following <?php ?> </li>
                  <li class="hidden sm:block"><a>Logout</a></li>
                </ul>
              </div>
            </ul>
          </div>
        </div>
        <!-- Page content here -->
        <?= $content ?? '' ?>
      </div>
      <div class="drawer-side">
        <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu flex pt-40 p-4 w-80 min-h-full bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-60 bg-indigo-900">
          <!-- Sidebar content here -->
          <div class="dropdown dropdown-end p-3">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
              <div class="w-10 rounded-full">
                <img alt="Tailwind CSS Navbar component" src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
              </div>
            </div>
            <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-gray-900 rounded-box w-52">
              <li>
                <a class="justify-between">
                  Profile
                  <span class="badge">New</span>
                </a>
              </li>
              <li><p class="justify-between">Followers <?php ?> </p></li>
              <li><p>Following <?php ?> </p></li>
              <li><a>Logout</a></li>
            </ul>
          </div>
          <li><a class="p-5" href="/">Home</a></li>
          <li><a class="p-5">Profile</a></li>
          <li><a class="p-5">Login</a></li>
          <li><a class="p-5">Liked</a></li>
        </ul>
      </div>
    </div>
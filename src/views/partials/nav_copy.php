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
                <div class="flex-none hidden lg:block">
                    <ul class="menu menu-horizontal">
                        <!-- Navbar menu content here -->
                        <li><a href="/">Home</a></li>
                        <li><a>Profile</a></li>
                        <li><a>Login</a></li>
                        <li><a>Liked</a></li>
                        <li class="form-control text-white">
                              <input type="text" placeholder="Search" class="input input-bordered input-primary w-24 md:w-auto text-white" />
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Page content here -->
            <?= $content ?? '' ?>
        </div>
        <div class="drawer-side">
            <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu flex gap-5 pt-40 p-4 w-80 min-h-full bg-clip-padding backdrop-filter backdrop-blur-xl bg-opacity-60 bg-indigo-900">
                <!-- Sidebar content here -->
                <li><a class="p-5" href="/">Home</a></li>
                <li><a class="p-5">Profile</a></li>
                <li><a class="p-5">Login</a></li>
                <li><a class="p-5">Liked</a></li>
            </ul>
        </div>
    </div>
<div>
    <div class="relative flex flex-col justify-center h-screen overflow-hidden text-black">
        <div class="w-full p-6 m-auto bg-white rounded-md shadow-md ring-2 ring-gray-800/50 lg:max-w-lg">
            
            <h1 class="text-3xl font-semibold text-center text-gray-700">Welcome Back!</h1>
            <form class="space-y-4" action="" method="post">
                <div>
                    <?php if (isset($data['errors']['password']) ): ?>
                        <div class="error text-center text-sm text-red-600 font-medium dark:text-red"><?php echo $data['errors']['password'][0]; ?></div>
                    <?php endif; ?>
                    <label class="label">
                        <span class="text-base label-text">Username</span>
                    </label>
                    <input type="text" name="username" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:ring-gray-500" required for="usuario">
                </div>
                <div>
                    <label class="label">
                        <span class="text-base label-text text-black">Password</span>
                    </label>
                    <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:ring-gray-500" required>
                </div>
                <p>&nbsp</p>
                <hr>
                <button class="btn btn-block border-2 bg-purple-800 text-white hover:bg-white hover:text-purple-800 hover:border-purple-700" type="submit">Login</button>
                <p>&nbsp</p>
                <hr>
                <a class="btn btn-block border-2 bg-gray-800 text-white hover:bg-white hover:text-gray-800 hover:border-gray-700" href="/register">Register a new account</a>
            </form>
        </div>
    </div>
</div>
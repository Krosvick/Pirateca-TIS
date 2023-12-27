<div>
    <div class="relative flex flex-col justify-center h-screen overflow-hidden text-black">
        <div class="w-full p-6 m-auto bg-white rounded-md shadow-md ring-2 ring-gray-800/50 lg:max-w-lg">
            <h1 class="text-3xl font-semibold text-center text-gray-700">Join Us!</h1>
            <form class="space-y-4" action="/register" method="POST">
                <div>
                  <label for="username" class="block mb-2 text-sm text-gray-600 font-medium dark:text-black">Ingress username</label>
                  <input type="text" id="username" name="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:ring-gray-500"  placeholder="" required>
                  <?php if (isset($data['errors']['username'])): ?>
                    <div class="error text-sm text-red-600 font-medium dark:text-red"><?php echo $data['errors']['username'][0]; ?></div>
                  <?php endif; ?>
                </div>
                <div>
                  <label for="password" class="block mb-2 text-sm text-gray-600 font-medium dark:text-black">Ingress Password</label>
                  <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:ring-gray-500"  required>
                  <?php if (isset($data['errors']['password'])): ?>
                    <div class="error text-sm text-red-600 font-medium dark:text-red"><?php  echo $data['errors']['password'][0]; ?></div>
                  <?php elseif(isset($data['errors']['hashed_password'])): ?>
                    <div class="error text-sm text-red-600 font-medium dark:text-red"><?php  echo $data['errors']['hashed_password'][0]; ?></div>
                  <?php endif; ?>
                </div>
                <div>
                  <label for="confirm_password" class="block mb-2 text-sm text-gray-600 font-medium dark:text-black">Confirm Password</label>
                  <input type="password" id="password" name="confirm_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:ring-gray-500"  required>
                </div>
                <div>
                    <p>&nbsp</p>
                    <hr>
                    <button class="btn btn-block border-2 bg-gray-800 text-white hover:bg-white hover:text-gray-800 hover:border-gray-700" type="submit">Registrarse</button>
                </div>
            </form>
        </div>
    </div>
</div>
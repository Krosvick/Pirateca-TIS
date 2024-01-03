<div hx-ext="class-tools" class="w-full bg-gray-900 mt-1 rounded-lg p-5 hidden text-gray-400" aria-label="commentary" classes="remove hidden" hx-trigger="click">
    <div class="w-full flex items-center gap-3">
        <div>
            <img class="w-10 h-10 me-4 rounded-full" src="https://img.icons8.com/nolan/64/user-default.png" alt="userImage">
        </div>
        <div>
            <p class="text-xs text-gray-200"><?=$user->get_username()?></p>
            <p class="font-light text-md"><?= $comment ?></p>
        </div>
    </div>
</div>
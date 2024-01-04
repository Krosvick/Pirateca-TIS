<section class="min-h-screen min-w-screen flex justify-center items-center py-10 text-black">
    <div class="bg-white h-full w-2/3 px-5 md:w-1/3 rounded-xl flex items-center flex-col py-5">
        <h1 class="font-bold">Create movie</h1>
        <form action="" method="post" class="w-full flex flex-col items-center gap-5 justify-center">
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Movie Title</span>
                </div>
                <input type="text" placeholder="Type here" name="original_title" class="input input-bordered w-full max-w-xs" required/>
            </label>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Overview</span>
                </div>
                <input type="text" placeholder="Type here" name="overview" class="input input-bordered w-full max-w-xs" required />
            </label>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Director</span>
                </div>
                <input type="text" placeholder="Type here" name="director" class="input input-bordered w-full max-w-xs" required/>
            </label>
            <label class="form-control w-full max-w-xs">
                <div class="label">
                    <span class="label-text">Release date</span>
                </div>
                <input type="date" placeholder="Type here" name="release_date" class="input input-bordered w-full max-w-xs" required/>
            </label>
            <select class="select select-bordered w-full max-w-xs" name="adult" required>
                <option disabled selected>Adult content?</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            <input type="hidden" placeholder = "Type here" class="input input-bordered w-full max-w-xs" name="poster_path" value="https://www.movienewz.com/img/films/poster-holder.jpg" />
            <input type="hidden" placeholder = "Type here" class="input input-bordered w-full max-w-xs" name="poster_status" value="1" />
            <button type="submit" class="btn btn-primary">Create movie</button>
        </form>
    </div>


</section>
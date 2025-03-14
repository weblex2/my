<div class="index-card">
    <a href="#">
        <img class="" src="{{Storage::url($img)}}" alt="" />
    </a>
    <div class="flex flex-col flex-grow p-5">
        <div class="h-20">
            <a href="#">
                <h5 class="header">{{$header}}</h5>
            </a>
        </div>
        <p class="flex-grow mb-3 font-normal text-gray-700 dark:text-gray-400">Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.</p>
        <a href="{{route($link)}}" class="read-more">
            Read more
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>
</div>

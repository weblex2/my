{{-- Modal --}}
    <div id="delete-notification-modal" class="invisible fixed z-20 bg-black bg-opacity-70 top-0 left-0 w-screen h-screen flex items-center justify-center">
      <div class="bg-zinc-800 rounded px-10 py-10 relative w-[500px]">
        <div class="absolute top-0 right-0 cursor-pointer m-3 shadow-xl" onclick="$('#delete-notification-modal').css('visibility', 'hidden')">
            <i class="fa-solid fa-xmark text-orange-500"></i></div>
            <h1>Are you sure?</h1>
            <input type="hidden" name="delete-notification-id" value="" id="delete-notification-id" />
            <button type="button" class="deleteConfim my-4 rounded-xl px-5 py-3 bg-red-300 border border-red-900">Yes - Delete it!</button>
            <button type="button" class="my-4 rounded-xl px-5 py-3 bg-zinc-700 border border-zinc-900">Nope - Keep it!</button>
            @if (($message = Session::get('error')))
                <div class="p-5 border-red-900 bg-red-300 text-black rounded-xl mb-5">
                <strong>{{ $message }}</strong>
                </div>
            @endif
      </div>  
    </div>
    {{-- end modal --}}
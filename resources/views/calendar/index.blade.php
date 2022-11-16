<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kalender') }}  Show: {{$month}} {{$year}}
        </h2>
    </x-slot>

    @php
        dump($ev);
    @endphp
    <div class="py-2 w-full">
        <div class="w-full">
            <div class="float-left">
                <a href="/cal/{{$prevpage}}"> < </a>
            </div>
            <div class="float-right">
                <a href="/cal/{{$nextpage}}"> > </a>
            </div>
        </div>
        <div class="grid grid-cols-7 gap-4 p-5">
            @for ($i=0; $i<35; $i++)
                @php
                    $dat  = date('d.m.Y',$date + $i*(60*60*24));
                    if ($dat ==  date('d.m.Y',time())){
                        $class="bg-slate-300";
                    }
                    elseif (date('m',strtotime($dat)) == $month) {
                        $class='bg-slate-100';
                    }
                    else{
                        $class='bg-white';
                    }
                @endphp
                <div class="day h-32 border text-right border-slate-500 {{ $class }}">
                    <div class="h-4 text-sm">{{ $dat  }}</div>
                    <div class="h-28 overflow-hidden">
                        @isset ($ev[$dat])
                        @foreach ($ev[$dat] as $e)
                            <div class="w-full text-xs border border-slate-700 pl-1 text-left" >
                                <a href="#">{{ $e }} </a>
                            </div>
                        @endforeach
                        <div>1 </div>
                            <div>1 </div>
                            <div>1 </div>
                            <div>1 </div>
                            <div>1 </div>
                            <div>1 </div>
                        @endisset
                    </div>
                </div>
            @endfor
        </div>
    </div>



    <!-- Modal -->
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="fixed z-10 inset-0 invisible overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="interestModal">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                <form method="POST" action="{{ route("calendar.saveEvent") }}">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg @click="toggleModal" class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Deactivate account
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to deactivate your account? All of your data will be permanently removed. This action cannot be undone.
                                </p>

                                    @csrf
                                    Event: <input type="text" name="event" value="My Event"><br>
                                    From: <input type="text" name="event_time_start" value="15:00"><br>
                                    To: Event: <input type="text" name="event_time_end" value="16:00">
                                    <input type="hidden" name="event_type" value="usr">
                                    <input type="hidden" name="date" id="date" value="">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Deactivate
                    </button>
                    <button type="button" class="closeModal mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        $(function(){
           // select day
           $('.day').click(function(){
              //$(this).attr('bgorg', $(this).css('background-color'));
               $('.day').removeClass('day_selected');
              $(this).addClass('day_selected');
           });

           // open modal
            $('.day').on('dblclick', function(e){

                $('#date').val('2022-11-16');
                $('#interestModal').removeClass('invisible');
            });
            $('.closeModal').on('click', function(e){
                $('#interestModal').addClass('invisible');
            });

        });
    </script>

</x-app-layout>

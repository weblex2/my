<x-ntfy.layout>
       <div class="py-12 h-full">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="notification-list">
                <div class="flex mb-3">
                    <a id="newNotification" class="btn cursor-pointer align-left"><i class=" fa-solid fa-star text-yellow-500"></i> New</a>
                </div>
                <div class="flex">
                {{-- @foreach ($emoticons as $emoji)
                     <div class="ntfy-tag {{$emoji->xname}}">{!!$emoji->xdec!!}</div>
                @endforeach --}}
                </div>
                
                 @foreach($notifications as $notification)
                    <x-ntfy.notification 
                        :notification=$notification
                        mode='show'
                    ></x-ntfy.notification>  
                @endforeach
            </div>
        </div>
    </div>

    <x-ntfy.modal/>

    <script>
        
        // Edit Notification
        $('.notification-list').on('click','.editNtfy', function(){
            var id = $(this).closest('.notification').attr('ntfy_id');
            $.get('/notify/edit/'+id, function(response){
                el = $('#ntfy_'+id); 
                $('#ntfy_'+id).before(response);
                el.remove();
            });
        });

        // Cancel Edit
        $('.notification-list').on('click','.btn-cancel', function(){
            var id = $(this).closest('.notification').attr('ntfy_id');
            $.get('/notify/show/'+id, function(response){
                el = $('#ntfy_'+id); 
                $('#ntfy_'+id).before(response);
                el.remove();
            });
        });

        // new Notification
        $('#newNotification').click(function(){
            $.get('/notify/new', function(response){
                $('.notification').eq(0).before(response);
            });
        });

        // save Notification
        $('.notification-list').on('click','.btn-save', function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(this).closest('.notification ').find('select, textarea, input').each(function(index, el){
                console.log(el);
            })

            values = $(this).closest('.notification ').find(':input').serialize();
            var id = $(this).closest('.notification').attr('ntfy_id');
            $.ajax({
                url: "/notify/save",
                type: "post",
                data: values ,
                success: function (response) {
                    el = $('#ntfy_'+id); 
                    $('#ntfy_'+id).before(response);
                    el.remove();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

        // Delete Notification (opens Modal)
        $('.notification-list').on('click','.deleteNotification', function(){
            var id = $(this).closest('.notification').attr('ntfy_id');
            $('#delete-notification-id').val(id);
            $('#delete-notification-modal').css('visibility', 'visible');
        });

        // Confirm Deletion
        $('#delete-notification-modal').on('click','.deleteConfim', function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var id = $('#delete-notification-id').val();
            $.ajax({
                url: "/notify/delete",
                type: "post",
                data: 'id='+id ,
                success: function (response) {
                    el = $('#ntfy_'+id); 
                    $('#ntfy_'+id).before(response);
                    el.remove();
                    $('#delete-notification-modal').css('visibility', 'hidden');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });

        // Click on a predefined Tag
        $('.notification-list').on('click','.usable-tag', function(){
            $(this).closest('.notification').find('.usedTags').append('<div class="ntfy-tag">'+$(this).html()+'</div>');
            var val = $('#tags').val() +$(this).attr('name')+",";
            $('#tags').val(val);
        });

        // Add a custom tag
        $('.notification-list').on('click','.add-tag', function(){
            var val = $('#new-tag').val();
            $(this).closest('.notification').find('.usedTags').append('<div class="ntfy-tag">'+val+'</div>');
            var val = $('#tags').val() +val+",";
            $('#tags').val(val);
        });
       

    </script>
</x-ntfy.layout>

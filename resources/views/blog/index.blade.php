<x-blog.blog-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="py-12 h-screen overflow-auto">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3">
            <div class="overflow-hidden h-full sm:rounded-lg">
                <div class="p-3 m-5">
                    @foreach ($posts as $post)
                        <x-blog.blog-item :post="$post" />    
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>

        function saveComment(){
            var comment  = $('frmtmp').find('.comment').val();
            var data = $('#frmtmp').serialize();
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },        
                type: 'POST',
                url: '{{ url("blog/makeComment") }}',
                data: data,
                success: function (data){
                    var blog_id = data.blog_id;
                    $('#frmtmp').after(data);
                    $('#frmtmp').remove();
                    var x  = 1;
                },
                error: function( data) {
                    console.log(data);
                }
            });
        }

        $(function() { 
            $('.comment-post').click(function(){
                var blog_id = $(this).closest('.blog').attr('blog_id');
                $.get(
                    "blog/newComment/"+ blog_id,
                    function (data) {
                        
                        $('#blog-'+ blog_id).find('.blog-comments').prepend(data);
                    }
                );

                $( ".divNewComment" ).load("resources/views/blog/createComment.blade.php");
            });
        });
    </script>    

</x-blog.blog-layout>

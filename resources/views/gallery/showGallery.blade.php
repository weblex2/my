<x-gallery-layout>
    <div id="debug" class="hidden fixed top-0 left-0 bg-gray-900 p-10 z-10 text-white">debug</div>
    <div id="debug2" class="hidden fixed top-0 right-0 bg-gray-900 p-10 z-10 text-white">debug2</div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-orange-500">
            <a href="/gallery1">{{ __('Gallery') }}</a>
        </h2>
    </x-slot>
    <div id="scroll" class="flex flex-col w-full h-[872px] bg-zinc-800 items-center overflow-auto p-4">
            <div id="gallery_content" class="content-center lg:max-w-[40%] md:max-w-[80%] rounded bg-zinc-900">
            @foreach ($pics as $i => $pic)
                <div class="flex justify-center bg-zinc-900 p-5 w-fit">
                    <x-gallery-item :pic="$pic" content="{{$pic->text}}" />
                </div> 
            @endforeach 
            </div>    
    </div>  
    <div id="deletePopup" class=" fixed top-0 left-0 w-screen h-screen flex items-center justify-center bg-zinc-900 bg-opacity-80 invisible">
        <div>
            <div class="p-5 font font-extrabold text-orange-500">
                <i class="fa-solid fa-trash gallery-delete-icon"></i> Really delete this blog item?
            </div>
                <input type="hidden" id="delete_id" name="id" value="" />
                <div class="float-left">
                    <a href="javascript:void(0)" onclick="deleteBlogItem()" class="mt-5 my-5 px-5 py-2 bg-zinc-900 border border-zinc-900 rounded-xl">Yes! Delete it!</a>
                </div> 
                <div class="float-right">
                    <a href="javascript:void(0)" onclick="closeDeletePopup()" class="mt-5 my-5 px-5 py-2 bg-zinc-900 border border-zinc-900 rounded-xl">No! I think about it...</a>
                </div> 
        </div>    
    </div>  
    <script>
        var gallery_id = <?php echo $gal_id; ?>;
        var top=0;
        var noMore = false;
        var lastpictop=0;
        var scrolled = 0;
        var offset = 1;
        $('#scroll').scroll(function(lastPictop) {
            var alreadyScrolled = parseInt($('#scroll').scrollTop());
            var img  = $('#scroll img').eq(-2);
            if (img.length==0){
                img = $('#scroll img').eq(0);
            }
            var imgHeight = parseInt(img.height());
            this.lastpictop = img.offset().top + imgHeight ;
            var scrollAndTop = alreadyScrolled + imgHeight;
            //$('#debug').html("<div>scrolled : "+ alreadyScrolled + " / lastpictop: "+ this.lastpictop +"</div>");
            //$('#debug').append("<div>img height : "+ imgHeight + "</div>");
            //$('#debug').append("<div>scroll &  height : "+ scrollAndTop + "</div>");
            
            if (scrollAndTop > this.lastpictop){
                console.log("jezte");  
                if (noMore==false){
                    more();
                }    
            }
        });
        
        $(function () {
            var img  = $('#scroll img').eq(-2);
            if (img.length > 0){
                var top = parseInt(img.offset().top); 
                var imgHeight = parseInt(img.height());
            }
            else{
                top=0;
                imgHeight=0;
            }
            this.lastpictop = top;
           //img.css('border', '10px solid red');
            $('#debug').append("<div>img height : "+ imgHeight + "</div>");
            var currentMousePos = { x: -1, y: -1 };

            $(document).mousemove(function(event) {
                currentMousePos.x = event.pageX;
                currentMousePos.y = event.pageY;
                $('#debug2').html("<div>X = " + currentMousePos.x +"</div><div>Y = " + currentMousePos.y+"</div>");
            });

            //more();
            
        });

       function showDeletePopup(id){
            $('#delete_id').val(id) ;
            $('#deletePopup').css('visibility', 'visible');
       }

        function closeDeletePopup(){
            $('#deletePopup').css('visibility', 'hidden');;
        }
       
        function deleteBlogItem(){
            var id = $('#delete_id').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                type: 'POST',
                url: '/gallery/deletepic',
                data: {
                    item_id: id,
                },
                async: false,
                success: function (data){
                    console.log("Data " + id+ " deleted");
                    $('#'+id).remove();
                    closeDeletePopup();
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function more(){
            $.ajax({
                type: 'GET',
                url: '/showMore/'+gallery_id+"/"+offset,
                async: false,
                success: function (data){
                    console.log("Data loaded");
                    if (data == -1){
                        noMore=true;
                        $('#debug').html("<div>no More!</div>");
                        return;
                    }
                    $('#gallery_content').append(data);
                    $('#scroll img').css('border', 'none');
                    var img  = $('#scroll img').eq(-2);
                    lastpictop = parseInt(img.offset().top); 
                    var imgHeight = parseInt(img.height());
                    lastpictop = img.offset().top + imgHeight ;
                    offset++;
                    $('#debug').html("<div>lastpictop set to : "+ lastpictop + "</div>");
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }
    </script>    
</x-gallery-layout>
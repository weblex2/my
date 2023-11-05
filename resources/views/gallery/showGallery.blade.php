@php
    $directory = storage_path('gallery/test');
    $files = scandir($directory);
    foreach($files as $i => $file){
        if (in_array($file,[".",".."]) || is_dir(storage_path('gallery/test')."/".$file)){
            unset($files[$i]);
        }
    }
@endphp    

<x-gallery-layout>
    <div id="debug" class="hidden fixed top-0 left-0 bg-gray-900 p-10 z-10 text-white">debug</div>
    <div id="debug2" class="hidden fixed top-0 right-0 bg-gray-900 p-10 z-10 text-white">debug2</div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            <a href="/gallery">{{ __('Gallery') }}</a>
            <a href="/gallery/upload" class="float-right">Upload Pic</a>
        </h2>
    </x-slot>
    <div id="scroll" class="flex flex-col w-full h-[872px] bg-zinc-800 items-center overflow-auto p-4">
            <div id="gallery_content" class="content-center max-w-[40%] md:max-w-[80%] rounded bg-zinc-900">
            {{-- @foreach ($pics as $i =>  $file)
                <div class="flex justify-center bg-zinc-900 p-5 w-fit">
                    <x-gallery-item pic='{{$file->pic}}' content="blubb" />
                </div> 
            @endforeach  --}}
            </div>    
    </div>    
    <script>
        var top=0;
        var noMore = false;
        var lastpictop=0;
        var scrolled = 0;
        var offset = 0;
        $('#scroll').scroll(function(lastPictop) {
            var alreadyScrolled = parseInt($('#scroll').scrollTop());
            var img  = $('#scroll img').eq(-2);
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

            more();
            
        });

       
        
       
        function more(){
            $.ajax({
                type: 'GET',
                url: '/showMore/'+offset,
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
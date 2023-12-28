<x-gallery-layout>
    <div id="debug" class="hidden fixed top-0 left-0 bg-gray-900 p-10 z-10 text-white">debug</div>
    <div id="debug2" class="hidden fixed top-0 right-0 bg-gray-900 p-10 z-10 text-white">debug2</div>
    <x-slot name="header">
        <h2 class="font-semibold leading-tight text-orange-500">
            <a href="/travel-blog">{{ __('Blog') }}</a> / 
            <a href="/travel-blog/show/{{$gallery[0]->code}}"> {{$gallery[0]->name}} </a> / 
            <span id="mp-name">{{$mp->mappoint_name}}</span>
        </h2>
    </x-slot>
    <div id="scroll" class="flex flex-col w-full h-[872px] bg-zinc-800 items-center overflow-auto p-4">

            @if ($message = Session::get('success'))
                <div class="p-5 border-green-900 bg-green-300 text-black rounded-xl mb-5">
                    <strong>{{ $message }}</strong>
                </div>
            @elseif (($message = Session::get('error')))
                <div class="p-5 border-red-900 bg-red-300 text-black rounded-xl mb-5">
                    <strong>{{ $message }}</strong>
                </div>
            @endif    
            
            @if (count($pics)>0)
                <div id="gallery_content">
                <div class="mappoint-header">{{$pics[0]->Mappoint->mappoint_name}}</div>
                @foreach ($pics as $i => $pic)
                        <x-gallery-item :pic="$pic" />
                @endforeach 
            @else
                <div class="mappoint-header">{{$mp->mappoint_name}}</div>    
                <div id="gallery_content" class="bg-green-200 content-center lg:max-w-[40%] md:max-w-[80%] rounded bg-zinc-900">
                <div class="bg-zinc-800"> sorry, noch keine pics hier...</div>
            @endif
            </div>    
    </div>  
    <div id="deletePopup" class=" fixed top-0 left-0 w-screen h-screen flex items-center justify-center bg-zinc-900 bg-opacity-80 invisible">
        <div>
            <div class="p-5 font font-extrabold text-orange-500">
                <i class="fa-solid fa-trash gallery-delete-icon"></i> Really delete this blog item?
            </div>
                <form id="frmDeleteBlogItem" action="{{route('gallery.deleteBlogItem')}}" method="POST">
                    @csrf
                    <input type="hidden" id="pic_id" name="id" value="" />
                    <div class="float-left mr-3">
                        <button type="submit" class="mt-5 my-5 px-5 py-2 bg-zinc-900 border border-zinc-900 rounded-xl">Yes! Delete it!</button>
                    </div> 
                    <div class="float-right">
                        <button onclick="closeDeletePopup()" class="mt-5 my-5 px-5 py-2 bg-zinc-900 border border-zinc-900 rounded-xl">No! I think about it...</button>
                    </div> 
                </form>    
        </div>    
    </div>  

    <div id="bigPicModal" class="fixed top-0 left-0 w-screen h-screen flex items-center justify-center bg-zinc-900 bg-opacity-100 invisible" onclick="$('#bigPicModal').css('visibility', 'hidden')">
        <div id="loaderBigPic">
            <img src="{{asset('img/loading2.webp')}}" class="w-20">
        </div>
        <div id="bigPic" class="p-10 hidden max-h-screen"></div>
    </div>  


    <script>
        var gallery_id = {{$gallery[0]->id}}; 
        var top=0;
        var noMore = false;
        var lastpictop=0;
        var scrolled = 0;
        var offset = 1;
        var noscroll = false;
        $('#scroll').scroll(function(lastPictop) {
            if (noscroll) return false;
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
            
            if (scrollAndTop > this.lastpictop - 100){
                 
                if (noMore!=true){
                    more();
                } 
                else{
                    console.log("nix mehr");
                }   
            }
        });

        $('#scroll').scroll(function() {
            if (noscroll) return false; 
            if( Math.round($('#scroll').scrollTop() + $('#scroll').height(),2) >= $('#gallery_content').height()) {
                console.log("no mor scrolling...");
                noscroll = true;
            }
        });
        
        $(function () {

            //Resize Main content
            var navHead = $('nav').outerHeight() + $('header').outerHeight();
            $('#main').css('height', $(window).height() - ($('nav').outerHeight() + $('header').outerHeight() +1));    

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
            $('#pic_id').val(id) ;
            $('#deletePopup').css('visibility', 'visible');
       }

        function closeDeletePopup(e){
            e.preventDefault();
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
                url: '/showMore/'+offset,
                async: false,
                success: function (data){
                    console.log("Data loaded");
                    console.log("Gallery End:" + data.gallery_end);
                    console.log("Gallery Mappoint:" + data.mp_name);
                    if (data.mp_name) {
                        $('#mp-name').text(data.mp_name);
                    }    
                    if (data.gallery_end == true){
                        console.log('End of Gallery');
                        console.log(data.alternatives);
                        $('#nextBlog').html(data.alternatives);
                        //$('#gallery_content').append("<div>End Of Mappoint (" + data.current_mappoint + ")</div>");
                        $('#gallery_content').append(data.html);
                        noMore=true;
                        $('#debug').html("<div>no More!</div>");
                        return false;
                    }
                    $('#gallery_content').append(data.html);
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
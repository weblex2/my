<x-yt2mp3.layout>
<div id="processing" class="hidden fixed top-0 left-0 w-screen h-screen">
    <div class="flex items-center justify-center w-full h-full">
    <img src={{asset('img/loading4.gif')}} class="">
    </div>
</div>    
<div class="py-12 h-full">
    <div class="w-[50%] mx-auto sm:px-6 lg:px-8 p-3 h-full">
        <div class="">
                <label for="yturl">Youtube URL:</label>
                <input type="text" class="w-full rounded-md mb-2" id="yturl" value="https://www.youtube.com/watch?v=Qq4j1LtCdww"> 
                <button id="submit" class="btn">Convert</button>
        <div id="result"></div>
    </div>
</div>
</div>
<script>
    $('#submit').click(function(){
        $('#processing').show();
        convert();
    });
    function convert(){
        var url = $('#yturl').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
                
        $.ajax({
            type: 'POST',
            url: '{{route("yt2mp3.getMp3")}}',
            data: {
                url: url,
            },
            async: true,
            //dataType: "json",
            success: function (resp){
                $('#result').html(resp);
                $('#processing').hide();
            },
            
            error: function(resp) {
                console.log(resp);
                //console.log(status);
                //console.log(error);
            }
        });
    }
</script>
</x-yt2mp3.layout>
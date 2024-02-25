<x-ntfy.layout>
<div class="py-12 h-full">
    <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
        <div class="mt-20">
                URL: <input type="text" id="yturl" value="https://www.youtube.com/watch?v=G1G9D8A4Fiw"> <button id="submit">Convert</button>
        <div id="result">result</div>
    </div>
</div>
</div>
<script>
    $('#submit').click(function(){
        convert();
    });
    function convert(){
        var url = $('#yturl').val();
        alert(url);
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
            success: function (resp){
                $('#result').html(resp);
                console.log(error);
            },
            error: function(data) {
                console.log('error');
            }
        });
    }
</script>
</x-ntfy.layout>
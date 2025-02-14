@extends('layouts.ssc')
@section('content')
<div class="container" >
    <div class="showCodeFiles">
        
        @foreach($structure as $item)
        <div class="p-1 m-0">
            <div>
                @if ($item['type']=="folder")
                    <div class="D">
                        {!! str_repeat('&nbsp;', $item['depth']*4) !!} 
                        <span class="dir"> 
                            <i class="dir-icon"></i>    
                            {!! $item['file']  !!}
                        </span>
                    </div>
                @elseif ($item['type']=="file")
                    <div>
                        <span class="file" path="{{ $item['path']  }}">
                            {!! str_repeat('&nbsp;', $item['depth']*4) !!} 
                            <i class="fa-regular fa-file text-gray-300"></i> 
                            {!! $item['file']  !!}
                        </span>
                    </div>
                @endif
            </div>    
        </div>
        @endforeach
    </div>

    <div class="showCodeContent"><textarea id='ta_content' disabled></textarea></div>
</div>

    <script>
        $().ready(function () {
            $('.file').click(function(){
                var path = $(this).attr('path');   
                $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },        
                type: 'POST',
                url: '{{ url("ssc/getCode") }}',
                data: {'path' : path},
                success: function (data){
                    var  content = data.content;
                    $('#ta_content').text(content);
                },
                error: function( data) {
                    console.log(data);
                }
            });
            });
        });

        
  
    </script>
@endsection
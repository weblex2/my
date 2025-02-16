@extends('layouts.ssc')
@section('content')
<div class="container" >
    <div class="showCodeFiles">
        
        @foreach($structure as $type => $typeRecords)
            <div class="laravel-type">{{$type}}</div> 
            @foreach ($typeRecords as $item)
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
                            <span class="file {{ str_replace("/","\\", $item['path']) == str_replace("/","\\",$startFile) ? "file-active" : ""  }}" path="{{ $item['path']  }}" title="{{ $item['path'] }}">
                                {!! str_repeat('&nbsp;', $item['depth']*4) !!} 
                                <i class="fa-regular fa-file text-gray-300"></i> 
                                {!! $item['file']  !!}
                            </span>
                        </div>
                    @endif
                </div>    
            </div>
             @endforeach
        @endforeach
    
    </div>
    
    <div class="showCodeContent">
        <pre><code id="code-block" class="language-php">{{$startFileContent}}</code></pre>
    </div>
</div>


    
    <script>
        $().ready(function () {
            //$('.file-active').click();

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
                    var  content    = data.content;
                    var  linecount  = data.linecount;
                    $('.linecount').html('');
                    for(let i = 1; i < linecount; i++) {
                        $('.linecount').append('<div class="line-number">'+i+'</div>');
                    }
                    console.log(linecount);
                    const codeBlock = document.getElementById('code-block');
                    
                    
                    // Prism.js Highlighting anwenden
                    
                    
                    textArr = content.split("\n");
                    console.log("ta length: " + textArr.length);
                    console.log("buchstaben: " + (textArr.length).toString().length);
                    var maxLines = (textArr.length).toString().length;
                    var pad = "0";
                    for(let i = 0; i < textArr.length; i++) {
                        textArr[i] =   String(pad.repeat(maxLines) + (i+1)).slice(-1*maxLines)  + " " +  textArr[i];
                    }
                    var textWithNumbers = textArr.join('\n');
                    codeBlock.textContent = textWithNumbers;
                    
                    Prism.highlightElement(codeBlock);
                },
                error: function( data) {
                    console.log(data);
                }
            });
            });
        });

        
  
    </script>
@endsection
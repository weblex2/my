<x-noppal>

<div class="container mx-auto flex-auto pt-20">

    <div class="flex justify-center "> 
   
    <img src="{{asset('img/noppal3.jpg')}}" class="img-me">
    </div>
    <br>
    <br>
        <div class="cv grid grid-cols-2 gap-0 ">
            <h1 class="col-span-2 text-center">Lebenslauf</h1>
            Edit  = {{$edit}}
            <hr class="col-span-2 mb-10">
            <div class="grid grid-cols-6">
                <h2 class="col-span-6  border-red-500">Persönliche Daten</h2>
                @foreach ($data['PD'] as $key => $pd)
                    <div class="header col-span-2">
                        {{$pd->name}}
                    </div>
                    <div class="bl_text mb-5  col-span-4">
                        {!!nl2br($pd['value'])!!}
                    </div>
                @endforeach

                <div class="col-span-6">
                    <hr class="mb-5">
                </div>    

                <div class="col-span-6">
                    <h2>Ausbildung</h2>
                </div>
                
                @foreach ($data['AU'] as $key => $pd)
                    
                     @php
                        if ($pd->date_to=="0000-00-00"){
                            $pd->date_to = "jetzt";
                        }
                    @endphp
                    <div class="header col-span-6">{{$pd->date_from}} - {{$pd->date_to}}</div>
                    <h3 class="col-span-6">{{$pd->header}}</h3>
                    <div class="bl_text mb-10  col-span-6">
                        {!!nl2br($pd['value'])!!}
                    </div>
                @endforeach
                
                <div class="col-span-6">
                    <hr class="mb-5">
                </div> 

                <div class="col-span-6">
                    <h2>Kenntnisse & Fähigkeiten</h2>
                </div>  

                @foreach ($data['LANG'] as $key => $kf)
                    <h3 class="col-span-1">{{$kf->header}}</h3>
                    <div class="bl_text  col-span-5">
                        {!!nl2br($kf['value'])!!}
                    </div>
                @endforeach 
                <div class="col-span-6">
                    <div class="my-10">
                    <span class="tag">PHP</span>
                    <span class="tag">Laravel</span>
                    <span class="tag">jQuery</span>
                    <span class="tag">mySQL</span>
                    <span class="tag">JS</span>
                    <span class="tag">PMS</span>

                    <span class="tag">CRM</span>
                    <span class="tag">SQL</span>
                    <span class="tag">Oracle</span>
                    <span class="tag">Windows</span>
                    <span class="tag">Linux</span>
                    <span class="tag">AWS</span>
                    <span class="tag">jQuery Bootstrap</span>
                    </div>
                </div>


            </div>
            <div class="grid grid-cols-1 row-span-12 ml-20">
                <h2 class="col-span-2">Berufliche Laufbahn</h2>
                @foreach ($data['BL'] as $key => $bl)
                <div class="col-span-2 bl">
                    <h3>{{$bl['header']}}</h3>
                    @php
                        if ($bl->date_to=="0000-00-00"){
                            $bl->date_to = "jetzt";
                        }
                    @endphp
                    <div class="header">{{$bl->date_from}} - {{$bl->date_to}}</div>
                    <div class="bl_text mb-10">{!!nl2br($bl['value'])!!}</div>
                </div>
                @endforeach
            </div>    
        </div>
        <button type="button" class="btn">Edit</button>
        <br>
<br>
</x-noppal>
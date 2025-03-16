<x-noppal>

<div class="container flex-auto pt-20 mx-auto">

    <div class="flex flex-col items-center justify-center ">
        <div><img src="{{asset('img/noppal3.jpg')}}" class="img-me"></div>
        <div class="downloadlink p-3 mt-5 border !border-zinc-700"><i class="fa-solid fa-download"></i> <a target="_blank" href="{{route("cv.pdf")}}"> [Download PDF]</a></div>
    </div>
    <br>
    <br>
        <div class="grid grid-cols-2 gap-0 cv ">
            <h1 class="col-span-2 text-center">Lebenslauf</h1>

            {{-- Edit  = {{$edit}} --}}
            <hr class="col-span-2 mb-10">
            <div class="grid grid-cols-6">
                <h2 class="col-span-6 ">Persönliche Daten</h2>
                @foreach ($data['PD'] as $key => $pd)
                    <div class="col-span-2 header">
                        {{$pd->name}}
                    </div>
                    <div class="col-span-4 mb-5 bl_text">
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
                    <div class="col-span-6 header">{{$pd->date_from}} - {{$pd->date_to}}</div>
                    <h3 class="col-span-6">{{$pd->header}}</h3>
                    <div class="col-span-6 mb-10 bl_text">
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
                    <div class="col-span-5 bl_text">
                        {!!nl2br($kf['value'])!!}
                    </div>
                @endforeach
                <div class="col-span-6">
                    <div class="my-10">
                    <span class="tag">PHP</span>
                    <span class="tag">Laravel</span>
                    <span class="tag">Laravel Filament</span>
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
            <div class="grid grid-cols-1 ml-20 row-span-12">
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
                    <div class="mb-10 bl_text">{!!nl2br($bl['value'])!!}</div>
                </div>
                @endforeach
            </div>
        </div>
        {{-- <button type="button" class="btn">Edit</button>
        <br> --}}
<br>
</x-noppal>

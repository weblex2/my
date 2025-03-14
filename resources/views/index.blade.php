<x-noppal>
<div class="index-wrapper">
    <div class="container flex-auto mx-auto noppindex">
        <div class="my-img-wrapper">
            <img src="img/noppal3.jpg" class="img-me">
        </div>

        <div class="flex items-center justify-center w-full text-black">
               <div>Powerd by:</div>
               <img class="floatleft icon" src="{{Storage::url('aws-icon.webp')}}" title="AWS">
               <img class="floatleft icon" src="{{Storage::url('ec2-icon.webp')}}" title="AWS EC2">
               <img class="floatleft icon" src="{{Storage::url('laravel-icon.webp')}}" title="Laravel">
               <img class="floatleft icon" src="{{Storage::url('filament-icon.jpg')}}" title="Filament">
               <img class="floatleft icon" src="{{Storage::url('mysql-icon.webp')}}" title="MySQL">
               <img class="floatleft icon" src="{{Storage::url('php-icon.webp')}}" title="PHP">
        </div>

        <div class="my-flex-box">

            <x-index.card
                img="card-laravel.png"
                header="Meine ersten Schritte mit Laravel"
                link="blog.index"
            />
            <x-index.card
                img="card-ntfy.webp"
                header="Der eigene NTFY Server"
                link="ntfy.test"
            />
            <x-index.card
                img="card-blog.jpg"
                header="Reise Blog"
                link="gallery.index"
            />
        </div>

        <div class="my-flex-box">
            <x-index.card
                img="card-crm.jpg"
                header="Erstes eigenes CRM mit Filament"
                link="filament.filament/admin.pages.dashboard"
            />

            <x-index.card
                img="card-futter.jpg"
                header="Futter Manager"
                link="futter.index"
            />
        </div>


        <div class="my-flex-box">
            <a href="/cv" class="box"><div>Lebenslauf</div></a>
            <a href="/futter" class="box"><div>Futter</div></a>
            {{-- <a href="/notify/test" class="box"><div>Ntfy</div></a> --}}
            <a href="{{route('phpmyadmin')}}" class="box"><div>phpMyAdmin</div></a>
            <!-- <a href="/chat?userid=25" class="box"><div>Chat (Reverb)</div></a> -->
        </div>

        <div class="my-flex-box">
            <a href="/askGemeni" class="box">
                <div class="flex items-center justify-center">
                    <img src="{{Storage::url('gemeni.png')}}">
                    Google Gemeni API
                </div>
            </a>
        </div>

    </div>
</div>

</x-noppal>

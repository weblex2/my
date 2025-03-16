<x-noppal>
<div class="index-wrapper">
    <div class="noppindex">
        <div class="my-img-wrapper">
            <img src="img/noppal3.jpg" class="img-me">
        </div>

        <div class="badges">
            <x-knowledge-badge text="AWS/EC2" />
            <x-knowledge-badge text="MySQL" />
            <x-knowledge-badge text="PHP 8.3" />
            <x-knowledge-badge text="Laravel v.11" />
            <x-knowledge-badge text="Filament" />
            <x-knowledge-badge text="Apache" />
            <x-knowledge-badge text="Tailwind" />
            <x-knowledge-badge text="jQuery" />
            <x-knowledge-badge text="Docker" />
        </div>

        <div class="my-flex-box">

            <x-index.card
                img="card-laravel.png"
                header="Meine ersten Schritte mit Laravel"
                text="Was ich auf der Reise mit Laravel so erlebt habe - ein Blog"
                link="blog.index"
            />
            <x-index.card
                img="card-ntfy.webp"
                header="Der eigene NTFY Server"
                text="Ein einfacher Weg, Nachrichten zu verschicken"
                link="ntfy.test"
            />
            <x-index.card
                img="card-blog.jpg"
                header="Reise Blog"
                text="Ich fahre auch gern mal weg, hier sind ein paar Einfrücke"
                link="gallery.index"
            />
        </div>

        <div class="my-flex-box">
            <x-index.card
                img="card-crm.jpg"
                header="Erstes eigenes CRM mit Filament"
                text="Das wollte ich schon immer einmal haben :)"
                link="filament.filament/admin.pages.dashboard"
            />

            <x-index.card
                img="card-futter.jpg"
                header="Futter Manager"
                text="Weil wir nie wissen, was wir morgen essen sollen, ihr kennt das..."
                link="futter.index"
            />

            <x-index.card
                img="card-phpmyadmin.webp"
                header="PhpMyAdmin"
                text="Nur für mich, weil es so einfacher ist"
                link="phpmyadmin"
            />

        </div>


        <div class="my-flex-box">
            <x-index.card
                img="card-cv.png"
                header="Lebenslauf"
                text="Ja nun, was soll ich sagen, ein Lebenslauf eben.."
                link="cv.index"
            />

            <x-index.card
                img="card-gemeni.png"
                header="Google Gemeni API"
                text="Eine einfache API zu Google Gemeni, ich wollte es halt mal ausprobieren"
                link="gemeni.index"
            />

            <x-index.card
                img="cv-maintainance.jpeg"
                header="Site Maintainance"
                text="Logs, Serverstatus usw... Was man eben braucht"
                link="maintainance.showLogs"
            />

        </div>


    </div>
</div>

</x-noppal>

<x-noppal>
<div class="index-wrapper">
    <div class="noppindex">
        <div class="my-img-wrapper">
            <img src="img/noppal3.jpg" class="img-me">
        </div>



        <div class="container flex items-center justify-center mb-20 text-black">
        <div class="w-full p-10 bg-white border rounded-lg shadow-lg border-zinc-200">
            <p class="text-lg text-center">
                Ich bin ein leidenschaftlicher Programmierer mit einem starken Fokus auf <strong>PHP</strong> und <strong>Laravel</strong>.
                In meiner Karriere habe ich mich intensiv mit der Entwicklung von Webanwendungen beschäftigt und bin ständig auf der Suche nach neuen Herausforderungen,
                um meine Fähigkeiten zu erweitern.
            </p>
            <p class="mt-4 text-lg text-center">
                Diese Seite ist mein persönliches Experimentierfeld, auf dem ich verschiedene Technologien und Konzepte ausprobiere.
                Von kleinen Tools und Features bis hin zu komplexen Anwendungen – hier möchte ich nicht nur zeigen, was ich in der <strong>Webentwicklung</strong> beherrsche,
                sondern auch kontinuierlich dazulernen und innovative Lösungen entwickeln.
            </p>
            <p class="mt-4 text-lg text-center">
                Schau dich gerne um und sieh dir an, welche Projekte ich aktuell umsetze!
            </p>
            <!-- Technologies Section -->
            <div class="mt-5 text-center">
                <h3 class="mb-4 text-xl font-semibold">Technologien, die ich benutze:</h3>
                <div class="flex flex-wrap justify-center gap-4">
                    <span class="px-4 py-2 text-sm text-white bg-blue-500 rounded-full">PHP</span>
                    <span class="px-4 py-2 text-sm text-white bg-green-500 rounded-full">Laravel</span>
                    <span class="px-4 py-2 text-sm text-white bg-green-500 rounded-full">Laravel Filament</span>
                    <span class="px-4 py-2 text-sm text-white bg-yellow-500 rounded-full">AWS</span>
                    <span class="px-4 py-2 text-sm text-white bg-yellow-500 rounded-full">Docker</span>
                    <span class="px-4 py-2 text-sm text-white bg-indigo-500 rounded-full">MySQL</span>
                    <span class="px-4 py-2 text-sm text-white bg-red-500 rounded-full">Apache</span>
                    <span class="px-4 py-2 text-sm text-white bg-teal-500 rounded-full">Tailwind</span>
                    <span class="px-4 py-2 text-sm text-white bg-teal-500 rounded-full">jQuery</span>
                </div>
            </div>
        </div>
    </div>



        {{-- <div class="badges">
            <x-knowledge-badge text="AWS/EC2" />
            <x-knowledge-badge text="MySQL" />
            <x-knowledge-badge text="PHP 8.3" />
            <x-knowledge-badge text="Laravel v.11" />
            <x-knowledge-badge text="Filament" />
            <x-knowledge-badge text="Apache" />
            <x-knowledge-badge text="Tailwind" />
            <x-knowledge-badge text="jQuery" />
            <x-knowledge-badge text="Docker" />
        </div> --}}


        <div class="my-flex-box">

            <x-index.card
                img="card-laravel.png"
                header="Meine ersten Schritte mit Laravel"
                text="Was ich auf der Reise mit Laravel so erlebt habe - ein Blog"
                link="blog.index"
            />

            <x-index.card
                img="card-reverb.png"
                header="Chat mit Laravel Reverb"
                text="Laravel Reverb brings real-time WebSocket communication for Laravel applications."
                link="chat.login"
            />


            <x-index.card
                img="card-ntfy.webp"
                header="Der eigene NTFY Server"
                text="Ein einfacher Weg, Nachrichten zu verschicken"
                link="ntfy.test"
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

        {{-- <div class="my-flex-box">
            <x-index.card
                img="card-blog.jpg"
                header="Reise Blog"
                text="Ich fahre auch gern mal weg, hier sind ein paar Einfrücke"
                link="gallery.index"
            />
        </div> --}}


    </div>
</div>

</x-noppal>

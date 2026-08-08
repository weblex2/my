<x-noppal>
    @vite([
        'resources/css/netnexus.css',
        'resources/js/netnexus.js',
        'resources/css/index3.css',
        'resources/js/index3.js',
    ])

    <div class="nn-root i3">
        <div class="nn-grid" aria-hidden="true"></div>
        <canvas class="nn-particles" data-nn-particles aria-hidden="true"></canvas>
        <div class="nn-scanlines" aria-hidden="true"></div>
        <div class="nn-vignette" aria-hidden="true"></div>

        {{-- Navigationsschiene --}}
        <nav class="i3-rail" aria-label="Abschnitte">
            <a class="i3-rail-mark" href="#i3-profil">Profil</a>
            <a class="i3-rail-mark" href="#i3-tech">Technik</a>
            <a class="i3-rail-mark" href="#i3-module">Module</a>
        </nav>

        {{-- Eck-HUD --}}
        <div class="i3-corner i3-corner--tl" aria-hidden="true">
            sys // <b>noppal</b><br>node 07 aktiv
        </div>

        <div class="i3-corner i3-corner--tr" aria-hidden="true">
            systemzeit<br><b data-nn-clock>--:--:--</b>
        </div>

        <div class="i3-corner i3-corner--br" aria-hidden="true">
            build <b>3.0</b>
        </div>

        {{-- Radar unten links --}}
        <div class="i3-radar" aria-hidden="true">
            <canvas data-i3-radar width="132" height="132"></canvas>
            <div class="i3-radar-meta">
                scan aktiv<br>
                kontakte <b data-i3-radar-count>00</b>
            </div>
        </div>

        <div class="nn-shell">

            <header class="nn-header">
                <h1 class="nn-brand">
                    NOPPAL
                    <span class="nn-sub">// index3</span>
                </h1>

                <div class="nn-readouts">
                    <div class="nn-readout">
                        <b>status</b>
                        <span class="nn-status">online</span>
                    </div>
                    <div class="nn-readout">
                        <b>module</b>
                        <span>10</span>
                    </div>
                </div>
            </header>

            <p class="nn-bootline" data-nn-boot></p>

            {{-- 01 --}}
            <section class="nn-section" id="i3-profil">
                <h2 class="nn-section-title"><span class="nn-idx">01</span> Profil</h2>

                <div class="nn-intro">
                    <figure class="nn-portrait">
                        <img src="{{ asset('img/noppal3.jpg') }}" alt="Alexander">
                        <figcaption>operator // alexander</figcaption>
                    </figure>

                    <div class="nn-bio nn-panel">
                        <p>
                            Ich bin ein leidenschaftlicher Programmierer mit einem starken Fokus auf
                            <strong>PHP</strong> und <strong>Laravel</strong>. In meiner Karriere habe ich mich
                            intensiv mit der Entwicklung von Webanwendungen beschäftigt und bin ständig auf der
                            Suche nach neuen Herausforderungen, um meine Fähigkeiten zu erweitern.
                        </p>
                        <p>
                            Diese Seite ist mein persönliches Experimentierfeld, auf dem ich verschiedene
                            Technologien und Konzepte ausprobiere. Von kleinen Tools und Features bis hin zu
                            komplexen Anwendungen – hier möchte ich nicht nur zeigen, was ich in der
                            <strong>Webentwicklung</strong> beherrsche, sondern auch kontinuierlich dazulernen
                            und innovative Lösungen entwickeln.
                        </p>
                        <p>
                            Schau dich gerne um und sieh dir an, welche Projekte ich aktuell umsetze!
                        </p>
                    </div>
                </div>
            </section>

            {{-- 02 --}}
            <section class="nn-section" id="i3-tech">
                <h2 class="nn-section-title"><span class="nn-idx">02</span> Technologien</h2>

                <div class="nn-stack">
                    <span class="nn-chip">PHP</span>
                    <span class="nn-chip">Laravel</span>
                    <span class="nn-chip">Laravel Filament</span>
                    <span class="nn-chip">AWS</span>
                    <span class="nn-chip">Docker</span>
                    <span class="nn-chip">MySQL</span>
                    <span class="nn-chip">Apache</span>
                    <span class="nn-chip">Tailwind</span>
                    <span class="nn-chip">jQuery</span>
                </div>
            </section>

            {{-- 03 --}}
            <section class="nn-section" id="i3-module">
                <h2 class="nn-section-title"><span class="nn-idx">03</span> Module</h2>

                <div class="nn-modules">
                    <div class="i3-tilt">
                        <x-index.card img="card-laravel.png" header="Meine ersten Schritte mit Laravel"
                            text="Was ich auf der Reise mit Laravel so erlebt habe - ein Blog" link="blog.index" />
                    </div>

                    <div class="i3-tilt">
                        <x-index.card img="card-reverb.png" header="Chat mit Laravel Reverb"
                            text="Laravel Reverb brings real-time WebSocket communication for Laravel applications."
                            link="chat.login" />
                    </div>

                    <div class="i3-tilt">
                        <x-index.card img="card-ntfy.webp" header="Der eigene NTFY Server"
                            text="Ein einfacher Weg, Nachrichten zu verschicken" link="ntfy.test" />
                    </div>

                    <div class="i3-tilt">
                        <x-index.card img="card-crm.jpg" header="Erstes eigenes CRM mit Filament"
                            text="Das wollte ich schon immer einmal haben :)" link="filament.admin.pages.dashboard" />
                    </div>

                    <div class="i3-tilt">
                        <x-index.card img="card-futter.jpg" header="Futter Manager"
                            text="Weil wir nie wissen, was wir morgen essen sollen, ihr kennt das..."
                            link="futter.index" />
                    </div>

                    <div class="i3-tilt">
                        <x-index.card img="card-phpmyadmin.webp" header="PhpMyAdmin"
                            text="Nur für mich, weil es so einfacher ist" link="phpmyadmin" />
                    </div>

                    <div class="i3-tilt">
                        <x-index.card img="card-cv.png" header="Lebenslauf"
                            text="Ja nun, was soll ich sagen, ein Lebenslauf eben.." link="cv.index" />
                    </div>

                    <div class="i3-tilt">
                        <x-index.card img="card-gemeni.png" header="Google Gemeni API"
                            text="Eine einfache API zu Google Gemeni, ich wollte es halt mal ausprobieren"
                            link="gemeni.index" />
                    </div>

                    <div class="i3-tilt">
                        <x-index.card img="cv-maintainance.jpeg" header="Site Maintainance"
                            text="Logs, Serverstatus usw... Was man eben braucht" link="maintainance.showLogs" />
                    </div>

                    <div class="i3-tilt">
                        <x-index.card img="cv-maintainance.jpeg" header="RedisInsight"
                            text="GUI für Redis... nettes Tool" link="maintainance.redisInsight" />
                    </div>
                </div>
            </section>

            <footer class="nn-footer">
                <span>noppal.de // index3</span>
                <span>
                    <a href="{{ url('/') }}">start</a> &nbsp;·&nbsp;
                    <a href="{{ url('/index2') }}">index2</a>
                </span>
            </footer>

        </div>
    </div>
</x-noppal>

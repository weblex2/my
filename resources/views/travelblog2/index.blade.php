<x-noppal>
    {{-- amCharts5 als klassische Skripte: laufen vor den Vite-Modulen --}}
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    @vite([
        'resources/css/netnexus.css',
        'resources/js/netnexus.js',
        'resources/css/travelblog2.css',
        'resources/js/travelblog2.js',
    ])

    @php($tb2Admin = auth()->check() && auth()->user()->hasAnyRole(['super_admin', 'admin']))

    <div class="nn-root tb2"
         data-countries-url="{{ route('tb2.countries') }}"
         data-feed-url="{{ route('tb2.feed') }}"
         @if ($tb2Admin)
         data-delete-url="{{ route('tb2.delete') }}"
         data-csrf="{{ csrf_token() }}"
         @endif>

        <div class="nn-grid" aria-hidden="true"></div>
        <canvas class="nn-particles" data-nn-particles aria-hidden="true"></canvas>
        <div class="nn-scanlines" aria-hidden="true"></div>
        <div class="nn-vignette" aria-hidden="true"></div>

        <div class="nn-shell">

            <header class="nn-header">
                <h1 class="nn-brand">
                    TRAVEL
                    <span class="nn-sub">// wo ich schon war</span>
                </h1>

                <div class="nn-readouts">
                    <div class="nn-readout">
                        <b>status</b>
                        <span class="nn-status">online</span>
                    </div>
                    <div class="nn-readout">
                        <b>systemzeit</b>
                        <span data-nn-clock>--:--:--</span>
                    </div>
                </div>
            </header>

            <p class="nn-bootline" data-nn-boot></p>

            <section class="nn-section">
                <div class="tb2-stats" data-tb2-stats></div>

                <div class="tb2-split">
                    <div class="tb2-mapbox">
                        <div class="tb2-map" data-tb2-map></div>
                        <div class="tb2-loading" data-tb2-loading>Karte wird geladen …</div>
                        <div class="tb2-maphint">Land oder Punkt anklicken</div>
                    </div>

                    <div class="tb2-list" data-tb2-list></div>
                </div>
            </section>

            <footer class="nn-footer">
                <span>noppal.de // travel-blog 2</span>
                <span>
                    @if ($tb2Admin)
                        <a href="{{ route('tb2.upload') }}">Fotos hochladen</a> &nbsp;·&nbsp;
                    @endif
                    <a href="{{ url('/travel-blog') }}">alte Ansicht</a>
                </span>
            </footer>

        </div>

        {{-- Feed: schiebt sich ins Bild, kein Seitenwechsel --}}
        <aside class="tb2-feed" data-tb2-feed aria-label="Fotostrom">
            <div class="tb2-feed-head">
                <div>
                    <h3 class="tb2-feed-title" data-tb2-feed-title>Reise</h3>
                    <span class="tb2-feed-sub" data-tb2-feed-sub></span>
                </div>
                <button type="button" class="tb2-close" data-tb2-close aria-label="Schließen">&times;</button>
            </div>

            <div class="tb2-feed-body" data-tb2-feed-body></div>
        </aside>
    </div>
</x-noppal>

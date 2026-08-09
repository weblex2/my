<x-noppal>
    @vite([
        'resources/css/netnexus.css',
        'resources/js/netnexus.js',
        'resources/css/travelblog2.css',
    ])

    <div class="nn-root tb2">
        <div class="nn-grid" aria-hidden="true"></div>
        <div class="nn-scanlines" aria-hidden="true"></div>
        <div class="nn-vignette" aria-hidden="true"></div>

        <div class="nn-shell">

            <header class="nn-header">
                <h1 class="nn-brand">
                    FREIGABE
                    <span class="nn-sub">// noch nicht sichtbar</span>
                </h1>

                <div class="nn-readouts">
                    <div class="nn-readout">
                        <b>offen</b>
                        <span>{{ $total }}</span>
                    </div>
                    @if ($total > 0)
                        <div class="nn-readout">
                            <b>seite</b>
                            <span>{{ $page->currentPage() }} / {{ $page->lastPage() }}</span>
                        </div>
                    @endif
                </div>
            </header>

            @if (session('tb2_msg'))
                <p class="nn-bootline">&gt; {{ session('tb2_msg') }}</p>
            @endif

            <section class="nn-section">
                @if ($total === 0)
                    <div class="tb2-end">Nichts offen — alle Fotos sind freigegeben.</div>
                    <div class="tb2-actions">
                        <a class="tb2-btn" href="{{ route('tb2.index') }}">Zur Karte</a>
                    </div>
                @else
                    <form method="POST" action="{{ route('tb2.approve') }}" id="tb2-form">
                        @csrf

                        <div class="tb2-actions">
                            <button type="button" class="tb2-btn" id="tb2-all">Alle auswählen</button>
                            <button type="button" class="tb2-btn" id="tb2-none">Auswahl aufheben</button>
                            <button type="submit" class="tb2-btn" name="action" value="approve">Freigeben</button>
                            <button type="submit" class="tb2-btn" name="action" value="delete"
                                    onclick="return confirm('Ausgewählte Fotos endgültig löschen?')">Löschen</button>
                            <a class="tb2-btn" href="{{ route('tb2.index') }}">Zur Karte</a>
                        </div>

                        <div class="tb2-queue" style="margin-top:1.6rem">
                            @foreach ($photos as $p)
                                @php $pic = $raw[$p['id']]; @endphp
                                <label class="tb2-item tb2-pick">
                                    <input type="checkbox" name="ids[]" value="{{ $p['id'] }}" checked>
                                    @if ($p['src'])
                                        <img src="{{ $p['src'] }}" alt="" loading="lazy">
                                    @endif
                                    <div class="tb2-item-state">
                                        {{ $galleries[$pic->gallery_id] ?? 'ohne Reise' }}<br>
                                        {{ $p['taken_at'] ?? 'ohne Datum' }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </form>

                    {{-- Blaettern. Ausserhalb des Formulars, damit ein Klick
                         nicht als Absenden zaehlt. --}}
                    @if ($page->lastPage() > 1)
                        <nav class="tb2-pager">
                            @if ($page->onFirstPage())
                                <span class="tb2-btn" aria-disabled="true">&lsaquo; zurück</span>
                            @else
                                <a class="tb2-btn" href="{{ $page->previousPageUrl() }}">&lsaquo; zurück</a>
                            @endif

                            <span class="tb2-pager-info">
                                {{ $page->firstItem() }}–{{ $page->lastItem() }} von {{ $total }}
                            </span>

                            @if ($page->hasMorePages())
                                <a class="tb2-btn" href="{{ $page->nextPageUrl() }}">weiter &rsaquo;</a>
                            @else
                                <span class="tb2-btn" aria-disabled="true">weiter &rsaquo;</span>
                            @endif
                        </nav>
                    @endif
                @endif
            </section>

            <footer class="nn-footer">
                <span>Freigegebene Fotos erscheinen sofort auf /travel-blog2</span>
            </footer>

        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            const form = document.getElementById('tb2-form');
            if (!form) return;
            const boxes = form.querySelectorAll('input[name="ids[]"]');
            document.getElementById('tb2-all').addEventListener('click', () => boxes.forEach(b => b.checked = true));
            document.getElementById('tb2-none').addEventListener('click', () => boxes.forEach(b => b.checked = false));
        })();
    </script>
    @endpush
</x-noppal>

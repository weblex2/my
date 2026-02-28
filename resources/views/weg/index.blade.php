<x-noppal>
    <div class="h-screen max-h-screen weg-wrapper" x-data="{
        selectedDoc: null,
        content: '',
        isLoading: false,
        openModal(url, docId) {
            console.log('Opening modal with URL:', url);
            this.isLoading = true;
            this.selectedDoc = docId;
            this.loadContent(url);
        },
        loadContent(url) {
            fetch(url).then(response => { if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`); return response.text() }).then(html => {
                console.log('Loaded content:', html);
                this.content = html;
                this.isLoading = false;
            }).catch(error => {
                console.error('Error loading content:', error);
                this.content = '<p>Fehler beim Laden des Dokuments</p>';
                this.isLoading = false;
            })
        },
        loadDocContent(docId) {
            console.log('Loading doc content for ID:', docId);
            this.isLoading = true;
            this.selectedDoc = docId;
            this.loadContent('{{ url('weg/content') }}/' + docId);
        },
        closeContent() {
            this.selectedDoc = null;
            this.content = '';
            this.isLoading = false;
            console.log('Content closed');
        }
    }">
        <div class="weg-menu">
            {{-- <div>
                <form method="GET" action="{{ url()->current() }}" class="flex items-center">
                    <input type="text" name="q" placeholder="Im Text suchen…" value="{{ request('q') }}"
                        class="border-r-0 rounded-r-none weg-input" />
                    <button type="submit" class="rounded-l-none weg-button">Suche</button>
                </form>
            </div> --}}

            <form method="GET" action="{{ url()->current() }}" class="flex items-center w-full max-w-md mb-4"
                x-data="{ searchQuery: '{{ request('q') }}' }">
                <label for="default-search"
                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" name="q" id="default-search" placeholder="Im Text suchen…"
                        x-model="searchQuery" class="border-r-0 rounded-r-none weg-search" />
                    <button type="submit"
                        class="absolute inset-y-0 flex items-center px-4 text-sm font-medium text-white bg-blue-700 border border-blue-700 rounded-lg right-10 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Search
                    </button>
                    <button type="button" x-show="searchQuery"
                        @click="searchQuery = ''; document.getElementById('default-search').value = ''; $el.closest('form').submit()"
                        class="absolute inset-y-0 right-0 flex items-center px-4 text-sm font-medium text-white bg-gray-500 border border-gray-500 rounded-lg rounded-l-none hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
            </form>

        </div>
        <div class="flex h-[calc(100%-64px)] overflow-hidden">
            <!-- Linke Seite: Dokumentenliste -->
            <div class="w-1/2 overflow-y-auto">
                <table class="w-full border-collapse weg_table">
                    <thead>
                        <tr>
                            <th class="p-2 border-b">Dokument Name</th>
                            <th class="p-2 border-b">From</th>
                            <th class="p-2 border-b">Subject</th>
                            <th class="p-2 border-b">Received</th>
                            <th class="p-2 border-b">Created</th>
                            <th class="p-2 border-b">Attachments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($docs as $index => $doc)
                            <tr @click.prevent="loadDocContent('{{ $doc->id }}')" class="cursor-pointer"
                                title="{{ $doc->name }}">
                                <td class="p-2 border-b">
                                    {{ Str::limit($doc->name, 40) }}
                                </td>
                                <td class="p-2 border-b">
                                    {{ Str::limit($doc->from_name, 40) }}
                                </td>
                                <td class="p-2 border-b weg-truncate" title="{{ $doc->subject }}">
                                    {{ Str::limit($doc->subject, 40) }}
                                </td>
                                <td class="p-2 border-b"
                                    title="{{ \Carbon\Carbon::parse($doc->received)->format('d.m.Y H:i:s') }}">
                                    {{ \Carbon\Carbon::parse($doc->received)->format('d.m.Y') }}
                                </td>
                                <td class="p-2 border-b"
                                    title="{{ \Carbon\Carbon::parse($doc->created_at)->format('d.m.Y H:i:s') }}">
                                    {{ \Carbon\Carbon::parse($doc->created_at)->format('d.m.Y') }}
                                </td>
                                <td class="p-2 border-b" @click.stop>
                                    @foreach ($doc->attachments as $attachment)
                                        <a href="#" class="inline-block weg-attachment"
                                            @click.prevent="openModal('{{ route('attachment.preview', ['id' => $attachment->id]) }}', '{{ $doc->id }}')">
                                            @switch($attachment->content_type)
                                                @case('application/pdf')
                                                    <i class="fa-regular fa-file-pdf" title="{{ $attachment->filename }}"></i>
                                                @break

                                                @case('image/jpeg')
                                                @case('image/jpg')

                                                @case('image/png')
                                                    <i class="fa-regular fa-file-image"
                                                        title="{{ $attachment->filename }}"></i>
                                                @break

                                                @case('application/vnd.ms-outlook')
                                                    <i class="fa-regular fa-envelope" title="{{ $attachment->filename }}"></i>
                                                @break

                                                @default
                                                    <i class="fa-solid fa-circle-question"
                                                        title="{{ $attachment->filename }}"></i>
                                            @endswitch
                                        </a>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Rechte Seite: Dokumentinhalt -->
            <div class="relative w-1/2 h-screen overflow-y-auto border-l bg-gray-50">
                <div x-show="selectedDoc" class="relative h-full">
                    <button @click="closeContent()" class="absolute top-0 right-0 p-2 text-2xl">&times;</button>
                    <div x-show="isLoading" class="p-4 text-center">
                        <p>Lädt...</p>
                    </div>
                    <div x-show="!isLoading && content" x-html="content" class=""></div>
                    <div x-show="selectedDoc && !content && !isLoading" class="">
                        <p>Bitte wählen Sie ein Dokument oder ein Attachment aus.</p>
                    </div>
                </div>
                <div x-show="!selectedDoc" class="p-4">
                    <p>Kein Dokument ausgewählt.</p>
                </div>
            </div>
        </div>
    </div>
</x-noppal>

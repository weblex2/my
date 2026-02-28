<!-- resources/views/weg/doc-content.blade.php -->
<!-- resources/views/weg/doc-content.blade.php -->
<div class="p-4">
    @if ($attachments && $attachments->isNotEmpty())
        <div class="my-4">
            <strong>Anhänge:</strong>
            <div class="flex flex-wrap gap-2">
                @foreach ($attachments as $attachment)
                    <a href="#" class="inline-block no-underline"
                        @click.prevent="openModal('{{ route('attachment.preview', ['id' => $attachment->id]) }}', '{{ $docId }}')">
                        @switch($attachment->content_type)
                            @case('application/pdf')
                                <div class="weg-attachment">
                                    <i class="fa-regular fa-file-pdf" title="{{ $attachment->filename }}"></i>
                                    {{ $attachment->filename }}
                                </div>
                            @break

                            @case('image/jpeg')
                            @case('image/jpg')

                            @case('image/png')
                                <div class="weg-attachment">
                                    <i class="fa-regular fa-file-image" title="{{ $attachment->filename }}"></i>
                                    {{ $attachment->filename }}
                                </div>
                            @break

                            @case('application/vnd.ms-outlook')
                                <div class="weg-attachment">
                                    <i class="fa-regular fa-envelope" title="{{ $attachment->filename }}"></i>
                                    {{ $attachment->filename }}
                                </div>
                            @break

                            @case('text/htm')
                                <div class="weg-attachment">
                                    <i class="fa-regular fa-file-code" title="{{ $attachment->filename }}"></i>
                                    {{ $attachment->filename }}
                                </div>
                            @break

                            @case('text/html')
                                <!-- Für .htm und .html Dateien -->
                                <div class="weg-attachment">
                                    <i class="fa-regular fa-file-code" title="{{ $attachment->filename }}"></i>
                                    {{ $attachment->filename }}
                                </div>
                            @break

                            @default
                                <div class="weg-attachment">
                                    <i class="fa-solid fa-circle-question" title="{{ $attachment->filename }}"></i>
                                    {{ $attachment->filename }}
                                </div>
                        @endswitch
                    </a>
                @endforeach
            </div>
        </div>
    @endif
    <div class="doc-content">
        {!! $body !!}
    </div>
</div>

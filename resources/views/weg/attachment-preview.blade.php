<div class="weg-iframe-wrapper">
    @if (str_starts_with($mime, 'image/'))
        <div class="flex justify-center">
            <img src="data:{{ $mime }};base64,{{ $base64 }}" class="max-w-full">
        </div>
    @elseif($mime === 'application/pdf')
        <iframe src="data:application/pdf;base64,{{ $base64 }}" width="100%" height="100%"></iframe>
    @elseif($mime === 'text/html')
        {
        <div class="flex justify-center">
            <img src="data:{{ $mime }};base64,{{ $base64 }}" class="max-w-full">
        </div>
        }
    @else
        <div class="flex justify-center">
            <pre class="items-center text-sm break-all whitespace-pre-wrap">{{ base64_decode($base64) }}</pre>
        </div>
    @endif
</div>

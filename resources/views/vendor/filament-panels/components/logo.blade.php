@php
    use App\Models\GeneralSetting;
    $logo = GeneralSetting::where('field', 'site_name')->value('value') ?? 'img/default-logo.png';
@endphp

<div class="flex">
{{-- <div class='mr-2'><img src="{{ asset('img/noppal3.jpg') }}"  alt='Noppal.de' class="w-10 mr-2 rounded-full"/></div> --}}
<div class="pl-3 mt-2 ml-3">{{$logo}}</div>
</div>

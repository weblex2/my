<div>
    
    <div class="grid grid-cols-7 items-justify ">
        <div class="day" date={{$datesDB[0]}}>{{$dates[0]}}</div>
        <div class="day" date={{$datesDB[1]}}>{{$dates[1]}}</div>
        <div class="day" date={{$datesDB[2]}}>{{$dates[2]}}</div>
        <div class="day" date={{$datesDB[3]}}>{{$dates[3]}}</div>
        <div class="day" date={{$datesDB[4]}}>{{$dates[4]}}</div>
        <div class="day" date={{$datesDB[5]}}>{{$dates[5]}}</div>
        <div class="day" date={{$datesDB[6]}}>{{$dates[6]}}</div>

        <div class="day1 " date={{$datesDB[0]}}>
            @isset($ft[$datesDB[0]]['img'])
                <img src="{{$ft[$datesDB[0]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesDB[1]}}>
            @isset($ft[$datesDB[1]]['img'])
                <img src="{{$ft[$datesDB[1]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesDB[2]}}>
            @isset($ft[$datesDB[2]]['img'])
                <img src="{{$ft[$datesDB[2]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesDB[3]}}>
            @isset($ft[$datesDB[3]]['img'])
                <img src="{{$ft[$datesDB[3]]['img']}}" class="rounded-full  w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesDB[4]}}>
            @isset($ft[$datesDB[4]]['img'])
                <img src="{{$ft[$datesDB[4]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesDB[5]}}>
            @isset($ft[$datesDB[5]]['img'])
                <img src="{{$ft[$datesDB[5]]['img']}}" class="rounded-full  w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesDB[6]}}>
            @isset($ft[$datesDB[6]]['img'])
                <img src="{{$ft[$datesDB[6]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
    </div>
    @php
        #dump($dates);
    @endphp
</div>

<script type="text/javascript">
   
</script>
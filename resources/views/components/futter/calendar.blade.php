<div>
    @php
        #dump($dates);
        #dump($datesdb);
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-7 w-full items-justify ">
        <div class="day" date={{$datesdb[0]}}>{{$dates[0]}}</div>
        <div class="day" date={{$datesdb[1]}}>{{$dates[1]}}</div>
        <div class="day" date={{$datesdb[2]}}>{{$dates[2]}}</div>
        <div class="day" date={{$datesdb[3]}}>{{$dates[3]}}</div>
        <div class="day" date={{$datesdb[4]}}>{{$dates[4]}}</div>
        <div class="day" date={{$datesdb[5]}}>{{$dates[5]}}</div>
        <div class="day" date={{$datesdb[6]}}>{{$dates[6]}}</div>

        <div class="day1 " date={{$datesdb[0]}}>
            @isset($ft[$datesdb[0]]['img'])
                <img src="{{$ft[$datesdb[0]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesdb[1]}}>
            @isset($ft[$datesdb[1]]['img'])
                <img src="{{$ft[$datesdb[1]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesdb[2]}}>
            @isset($ft[$datesdb[2]]['img'])
                <img src="{{$ft[$datesdb[2]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesdb[3]}}>
            @isset($ft[$datesdb[3]]['img'])
                <img src="{{$ft[$datesdb[3]]['img']}}" class="rounded-full  w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesdb[4]}}>
            @isset($ft[$datesdb[4]]['img'])
                <img src="{{$ft[$datesdb[4]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesdb[5]}}>
            @isset($ft[$datesdb[5]]['img'])
                <img src="{{$ft[$datesdb[5]]['img']}}" class="rounded-full  w-full h-full">
            @endisset
        </div>
        <div class="day1" date={{$datesdb[6]}}>
            @isset($ft[$datesdb[6]]['img'])
                <img src="{{$ft[$datesdb[6]]['img']}}" class="rounded-full w-full h-full">
            @endisset
        </div>
    </div>
</div>

<script type="text/javascript">
   
</script>
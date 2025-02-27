<div class="message user_{{$user_id}}">
    <p class="msg"><span class='channel'>[{{$channel}}]</span> 
        {{$message}}
    </p>
    <div class="msg-user">
        <span><img class='user-icon' src='{{$icon}}'></span> 
        {{ $nick }}, {{date('H:i:s')}}
    </div>
</div>
@php  
    if ($mode=="show"){
        $notification->reminder = $notification->reminder!="0000-00-00 00:00:00" ? \Carbon\Carbon::parse($notification->reminder)->format('d.m.Y H:i') : "-";
        $notification->date = $notification->date!="0000-00-00 00:00:00" ? \Carbon\Carbon::parse($notification->date)->format('d.m.Y H:i') : "-";
    }
@endphp
<div id="ntfy_{{$notification->id}}" ntfy_id="{{$notification->id}}" class="notification">

    <div class="grid-col col-span-4">
        @if ($mode=='edit')
            <input type="hidden" name="id" value="{{$notification->id}}">
            <input type="text" name="topic" value="{{$notification->topic}}">
        @else
            <h1 class="ntfyTopic">
                <i class="editNtfy fa-regular fa-pen-to-square cursor-pointer text-blue-500"></i>
                {{$notification->topic}} 
                <div class="float-right"><i class="fa-solid fa-xmark"></i></div>
            </h1>
        @endif
        
    </div>
    <div class="grid-col ntfyDesc col-span-4">
        @if ($mode=='edit')
            <input type="text" name="description" value="{{$notification->description}}">
        @else
            <h2>{{$notification->description}}</h2>
        @endif    
    </div>
    <div class="grid sm:grid-cols-1 md:grid-cols-3">
        <div class="grid-col md:col-span-1 mt-3">
            <div>Datum</div>
            @if ($mode=='edit')
                <input type="datetime-local" name="date" class="ntfyDate" value="{{$notification->date}}">
            @else
                <div class="ntfyDate header">{{$notification->date}}</div>
            @endif
        </div>    
        <div class="grid-col md:col-span-1 mt-3">
            <div >Erinnerung</div>
            @if ($mode=='edit')
                <input type="datetime-local" name="reminder" class="ntfyDate" value="{{$notification->reminder}}">
            @else
                <div class="ntfyDate header">{{$notification->reminder}}</div>
            @endif
        </div>    
        <div class="grid-col md:col-span-1 mt-3">
            <div >Wiederholung</div>
            @if ($mode=='edit')
                <select  name="recurring_interval" class="recurring_interval" value="{{$notification->recurring_interval}}">
                    <option value=""> - </option>
                    <option value="Y" {{$notification->recurring_interval=="Y" ?"selected" :"" }}>Jahr</option>
                    <option value="M">Monat</option>
                    <option value="D">Tag</option>
                    <option value="H">Stunde</option>
                </select> 
            @else
                <div class="ntfyRecurring">{{$notification->recurring_interval}}</div>
            @endif
        </div>    
    </div>  

    <div class="grid-col col-span-4 mt-3">
        <div >Tags</div>
        <div class="ntfyTags header">{{trim($notification->tags,',')}}</div>
    </div>  

    @if ($mode=='edit')
        <div>
            <button class="btn btn-save">Save</button> 
            <button class="btn btn-cancel">Abbrechen</button> 
        </div>
    @endif    
</div>  
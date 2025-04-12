
<div class="server ">
    <div class="mr-5"><i class="text-zinc-600 fa-solid fa-computer"></i> Server: {{ $server }}</div>
    <div class="mr-5"><i class="text-zinc-600 fa-solid fa-database"></i> Database: <span id="db" db="{{$db}}">{{ $db ?? "--none--" }}</span></div>
    @if (session()->has('table'))
    <div><i class="text-zinc-600 fa-solid fa-table-list"></i> Table: {{ session('table') }}</div>
    @endif

</div>


@if (request()->is('laravelMyAdmin/showTableStructure/*')  ||
     request()->is('laravelMyAdmin/editTableStructure*')  ||
     request()->is('laravelMyAdmin/new-table*')   ||
     request()->is('laravelMyAdmin/table-content*')
        )
     <div class="hidden menu md:flex">
        <div class="menu-item gradient"><a href="{{route("laravelMyAdmin.showTableContent", [ "db" => session("db"), "table" => session("table") ] ) }}">Show</a></div>
        <div class="menu-item gradient"><a href="{{route("laravelMyAdmin.showTableStructure", [ "db" => session("db"), "table" => session("table") ] ) }}">Structure</a></div>
        <div class="menu-item gradient">SQL</div>
        <div class="menu-item gradient">Search</div>
        <div class="menu-item gradient">Insert</div>
        <div class="menu-item gradient">Export</div>
        <div class="menu-item gradient">Import</div>
        <div class="menu-item gradient">Rights</div>
        <div class="menu-item gradient">Operations</div>
        <div class="menu-item gradient"><a href="{{route("laravelMyAdmin.clearCache")}}">Tools</a></div>
        <div class="relative menu-item gradient">
            <a href="{{route("laravelMyAdmin.migrations")}}">
                <x-laravel-my-admin.migration-badge />
            </a>
        </div>
    </div>

@elseif (request()->is('laravelMyAdmin/migrations*') )
        <div class="hidden menu md:flex">
        <div class="menu-item gradient">SQL</div>
        <div class="menu-item gradient">Operations</div>
        <div class="menu-item gradient"><a href="{{route("laravelMyAdmin.tools")}}">Git</a></div>
        <div class="menu-item gradient"><a href="{{route("laravelMyAdmin.clearCache")}}">Tools</a></div>
        <div class="menu-item gradient {{ ($db=="") ? "disabled" :"" }}" id="migUp" ><i class="text-green-500 fa-solid fa-arrow-up"></i> Migration Up</div>
        <div class="menu-item gradient" id="migDown"><i class="text-red-500 fa-solid fa-arrow-down"></i> Migration Down</div>
        <div class="menu-item gradient" id="migDown"><i class="text-yellow-500 fa-solid fa-arrows-up-down"></i> Migration Test (Up/Down)</div>
        <div class="relative menu-item gradient">
            <a href="{{route("laravelMyAdmin.migrations")}}">
                <x-laravel-my-admin.migration-badge />
            </a>
        </div>

    </div>
@else
    <div class="hidden menu md:flex">
        <div class="menu-item gradient">Datenbanken</div>
        <div class="menu-item gradient">SQL</div>
        <div class="menu-item gradient">Suche</div>
        <div class="menu-item gradient">Abfrage</div>
        <div class="menu-item gradient">Exportieren</div>
        <div class="menu-item gradient">Importieren</div>
        <div class="menu-item gradient"><a href="{{route("laravelMyAdmin.tools")}}">Git</a></div>
        <div class="relative menu-item gradient">
            <a href="{{route("laravelMyAdmin.migrations")}}">
                <x-laravel-my-admin.migration-badge />
            </a>
        </div>
    </div>

@endif

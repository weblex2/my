<div class="server ">
    <div class="mr-5"><i class="text-zinc-600 fa-solid fa-computer"></i> Server: {{ $server }}</div>
    <div class="mr-5"><i class="text-zinc-600 fa-solid fa-database"></i> Database: {{ $db }}</div>
    @if (session()->has('table'))
    <div><i class="text-zinc-600 fa-solid fa-table-list"></i> Table: {{ session('table') }}</div>
    @endif
</div>
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
        Migrations
        <span class="sr-only">Notifications</span>
        <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -end-2 dark:border-gray-900">20</div>
        </a>
    </div>


</div>

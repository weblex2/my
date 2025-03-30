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
</div>

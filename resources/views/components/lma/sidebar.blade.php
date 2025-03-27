<div class="sidebar">
    @foreach ($dbs as $dbname => $db)
        <div class="database-explorer">
            <div class="db" db_name="{{$dbname}}"><i class="fa-solid fa-database"></i> {{ $dbname }}</div>
            <div class="tables {{$dbname}}-tables">
                <div class="table newtable" db_name="{{$dbname}}"><a href="{{ route("laravelMyAdmin.newTable",["db" => $dbname]) }}"><i class="text-zinc-600 fa-solid fa-star"></i> new</a></div>
                @foreach ($db as $tablename => $table)
                    <div class="table" table_name="{{$dbname}}-{{$tablename}}"><i class="fa-solid fa-table-list"></i> {{ $tablename }}</div>
                    <div class="fields {{$dbname}}-{{ $tablename }}-fields">
                        @foreach ($table as $fieldname => $field)
                            <div class="field {{$dbname}}-field"><i class="fa-solid fa-table-columns"></i> {{ $fieldname }}</div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

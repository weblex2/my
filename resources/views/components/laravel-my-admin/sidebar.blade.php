<div>
    <a href="/laravelMyAdmin">Home</a>
    @foreach ($dbs as $dbname => $db)
        <div class="database-explorer">
            <div class="db" db_name="{{$dbname}}">
                <a href="{{route("laravelMyAdmin.viewDatabase",  ["db" => $dbname]) }}">
                    {!!$selected_db!=$dbname ? "<i class='fa-solid fa-square-plus'></i>" :"<i class='fa-solid fa-square-minus'></i>" !!}
                    <i class="fa-solid fa-database"></i> {{ $dbname }}</div>
                </a>
                <div class="tables {{$dbname}}-tables">
                <div class="table newtable {{$selected_db!=$dbname ? "hidden" :"" }}" db_name="{{$dbname}} ">
                    <i class="text-yellow-400 fa-solid fa-star"></i>
                    new
                </div>
                @foreach ($db as $tablename => $table)
                    <div class="table {{$selected_db!=$dbname ? "hidden" :"" }}" table_name="{{$dbname}}-{{$tablename}}" title={{$tablename}}>
                        <a href="{{ route("laravelMyAdmin.showTable", ["db" => $dbname, "table" => $tablename]) }}">
                            <i class="fa-solid fa-table-list"></i>
                            {{ $tablename }}
                        </a>
                    </div>
                    <div class="fields {{$dbname}}-{{ $tablename }}-fields ">
                        @foreach ($table as $fieldname => $field)
                            <div class="field"><i class="fa-solid fa-table-columns"></i> {{ $fieldname }}</div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

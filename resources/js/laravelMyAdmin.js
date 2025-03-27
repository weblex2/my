import './bootstrap';
import React from 'react';

import flatpickr from "flatpickr";
//import Alpine from 'alpinejs';

//window.Alpine = Alpine;

//Alpine.start();


 $(function () {
            /* $('.db').click(function(){
                //$('.tables').hide();
                //$('.fields').hide();
                let db = $(this).attr('db_name');
                $('.' + db + '-tables').toggle(200);
            }); */

            $('.table').click(function(){
                let tablename = $(this).attr('table_name');
                $('.' + tablename + '-fields').toggle(200);
            });


            $('.newtable').click(function(){
                let db = $(this).attr('db_name');
                $.ajax({
                    type: 'POST',
                    url: '{{route("laravelMyAdmin.createTable")}}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: { db: db } }
                    )
                .done(function(resp) { alert("success: " + resp.data)  })
                .fail(function() { alert("error"); })
                //.always(function() { alert("complete");
            });
        });

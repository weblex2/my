import './bootstrap';
import React from 'react';

import flatpickr from "flatpickr";
//import Alpine from 'alpinejs';

//window.Alpine = Alpine;

//Alpine.start();

$(function () {
    $('.toggledb').click(function () {
        let tableDiv = $(this).attr('db_name') + "-tables";
        // $(this).find('i').css('color', 'red');
        // $(this).toggleClass("fa-square-plus fa-square-minus");
        console.log('toggeling ' + tableDiv);
        $('.' + tableDiv).toggle(200);
    });

    function post(url, data, successCallback, errorCallback) {
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") // Falls Laravel CSRF-Schutz aktiv ist
            },
            success: function (response) {
                if (successCallback) successCallback(response);
            },
            error: function (xhr, status, error) {
                if (errorCallback) errorCallback(xhr, status, error);
            }
        });
    }

    function showModal(content, header) {
        $("#meinModal").find('.modal-body').html(content);
        $("#meinModal").modal("show");
    }

    window.truncateTable= function(table) {
        alert("Truncate Table: " + table);
    }

    window.addRowsToNewTable = function () {
        let after = $('#after').val();
        let amount = $('#amount').val();
        alert("amount: " + amount + " after: " + after);


        let data = {};
        data['amount'] = $('#amount').val();
        data['after'] = $('#after').val();
        //alert("amount" + amount + " after" + after);
        $('#loader').css('visibility','visible');
        $.ajax({
            url: '/laravelMyAdmin/add-rows-to-table',
            type: "POST",
            data: data,
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") // Falls Laravel CSRF-Schutz aktiv ist
            },
            success: function (response) {
                let afterTr = $('#'+after);
                afterTr.after(response.data);
                $('#loader').css('visibility', 'hidden');
                console.log(response);
            },
            error: function (xhr, status, error) {
                alert("no");
                //if (errorCallback) errorCallback(xhr, status, error);
                $('#loader').css('visibility','hidden');
            }
        });
    }



});




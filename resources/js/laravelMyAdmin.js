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


});




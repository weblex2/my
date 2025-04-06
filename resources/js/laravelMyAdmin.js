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
        var currentDisplay = $('.' + tableDiv).css('display');
        if (currentDisplay === 'none') {
            $('.' + tableDiv).css('display', 'block');
        } else {
            $('.' + tableDiv).css('display', 'none');
        }
        console.log('toggeling ' + tableDiv);
        //$('.' + tableDiv).toggle(200);
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
                //console.log(response);
            },
            error: function (xhr, status, error) {
                alert("no");
                //if (errorCallback) errorCallback(xhr, status, error);
                $('#loader').css('visibility','hidden');
            }
        });
    }

    $('#btnModifyTable').click(function() {
        modifyTable();
    });



    function modifyTable() {
        const rowDataAll = [];
        $('.tblLaravelMyAdmin tr[attribute]').each(function () {
            const rowData = {};
            $(this).find('td').find('input[name], select[name], textarea[name]').each(function () {
                const $field = $(this);
                const name = $field.attr('name');
                let value;

                if ($field.is(':checkbox')) {
                    value = $field.is(':checked') ? $field.val() : '0';
                } else if ($field.is('select') || $field.is('textarea') || $field.is(':text') || $field.is(':number') || $field.is(':email')) {
                    value = $field.val();
                } else {
                    // fallback
                    value = $field.val();
                }

                rowData[name] = value;
            });
            rowDataAll.push(rowData);
            console.log(rowData); // z. B. {column1: 'abc', active: '1', type: 'selectOption'}
        });

        // AJAX-Request
        $.ajax({
            url: '/laravelMyAdmin/modify-table', // Deine Zielroute
            method: 'POST',
            dataType: "json",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") // Falls Laravel CSRF-Schutz aktiv ist
            },
            data: {
                rows: JSON.stringify(rowDataAll)
            },
            success: function (response) {
                console.log('Erfolg:', response);
            },
            error: function (xhr, status, error) {
                console.error('Fehler:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
            }
        });
    }
});




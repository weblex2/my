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
    })
});

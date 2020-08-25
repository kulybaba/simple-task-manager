import '../css/app.scss';
import 'bootstrap';
import 'materialize-css/dist/js/materialize.min'
import bsCustomFileInput from 'bs-custom-file-input';
import $ from 'jquery';

bsCustomFileInput.init();

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

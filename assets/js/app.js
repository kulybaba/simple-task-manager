import '../css/app.scss';
import 'bootstrap';
import 'materialize-css/dist/js/materialize.min'
import bsCustomFileInput from 'bs-custom-file-input';
import './requests'
import $ from 'jquery';

bsCustomFileInput.init();

$(document).ready(function () {
    $('.alert-close').on('click', function () {
        $('.alert').removeClass('show');
    });
});

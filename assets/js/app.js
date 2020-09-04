import '../css/app.scss';
import 'bootstrap';
import 'materialize-css/dist/js/materialize.min'
import bsCustomFileInput from 'bs-custom-file-input';
import './requests';
import $ from 'jquery';
import Sortable from 'sortablejs';

bsCustomFileInput.init();

$(document).ready(function () {
    $('.alert-close').on('click', function () {
        $('.alert').removeClass('show');
    });

    $('.main-container').on('click', '.btn-edit-task', function () {
        let id = $(this).data('id');

        $(this).toggleClass('d-none');
        $('.btn-save-task-' + id).toggleClass('d-none');
        $('.btn-cancel-task-' + id).toggleClass('d-none');
        $('.btn-sort-task-' + id).toggleClass('d-none');
        $('.btn-delete-task-' + id).toggleClass('d-none');
        $('.task-checkbox-' + id).toggleClass('d-none');
        $('.task-text-' + id).toggleClass('d-none');
        $('.task-input-' + id).toggleClass('d-none');
        $('.task-input-' + id).val($('.task-text-' + id).text());
    });

    $('.main-container').on('click', '.btn-cancel-task', function () {
        let id = $(this).data('id');

        $(this).toggleClass('d-none');
        $('.btn-save-task-' + id).toggleClass('d-none');
        $('.btn-sort-task-' + id).toggleClass('d-none');
        $('.btn-edit-task-' + id).toggleClass('d-none');
        $('.btn-delete-task-' + id).toggleClass('d-none');
        $('.task-checkbox-' + id).toggleClass('d-none');
        $('.task-text-' + id).toggleClass('d-none');
        $('.task-input-' + id).toggleClass('d-none');
    });


    $.each($('.task-list'), function (index, element) {
        Sortable.create(element, {
            handle: '.fa-sort',
            animation: 150
        });
    });
});

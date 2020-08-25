import $ from 'jquery';

$(document).ready(function () {
    // Add task
    $('.btn-add-task').on('click', function () {
        let id = $(this).data('id');
        let text = $('.add-task-input-' + id).val();

        $.post('/tasks/add/' + id, JSON.stringify({ text: text })).done(function (data) {
            $('.task-list-' + id).append(`
                <li class="list-group-item list-group-item-action">
                    <input class="task-checkbox" type="checkbox">
                    <span class="task-text">${text}</span>
                    <div class="list-group-actions float-right">
                        <a href="#" data-toggle="tooltip" data-placement="left" title="Sort"><i class="fas fa-sort"></i></a>
                        <a href="#" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fas fa-pen"></i></a>
                        <a href="#" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </li>
            `);
        });
    });
})

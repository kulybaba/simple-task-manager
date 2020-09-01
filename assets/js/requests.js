import $ from 'jquery';

$(document).ready(function () {
    // Add task
    $('.btn-add-task').on('click', function () {
        let id = $(this).data('id');
        let text = $('.add-task-input-' + id).val();

        $.ajax({
            url: '/api/task',
            method: 'POST',
            data: JSON.stringify({
                text: text,
                projectId: id
            }),
            success: function (data) {
                if ($('.task-list-' + id).length === 0) {
                    $('.project-card-body-' + id).empty();
                    $('.project-card-body-' + id).append(`<ul class="list-group task-list task-list-${id}"></ul>`);
                }

                $('.task-list-' + id).append(`    
                    <li class="list-group-item list-group-item-action task-${data.task.id}">
                        <div class="row">
                            <div class="col-10">
                                <input class="task-checkbox task-checkbox-${data.task.id}" type="checkbox" data-id="${data.task.id}">
                                <span class="task-text task-text-${data.task.id}">${text}</span>
                                <input class="task-input task-input-${data.task.id} d-none" type="text">
                            </div>
                            <div class="col">
                                <div class="list-group-actions float-right">
                                    <a class="btn-save-task btn-save-task-${data.task.id} d-none" href="#" data-id="${data.task.id}"><i class="fas fa-save"></i></a>
                                    <a class="btn-cancel-task btn-cancel-task-${data.task.id} d-none" href="#" data-id="${data.task.id}"><i class="fas fa-times"></i></a>
                                    <a class="btn-sort-task btn-sort-task-${data.task.id}" href="#"><i class="fas fa-sort"></i></a>
                                    <a class="btn-edit-task btn-edit-task-${data.task.id}" href="#" data-id="${data.task.id}"><i class="fas fa-pen"></i></a>
                                    <a class="btn-delete-task btn-delete-task-${data.task.id}" href="#" data-id="${data.task.id}"><i class="fas fa-trash-alt"></i></a>
                                </div>
                            </div>
                        </div>
                    </li>
                `);

                $('.add-task-input-' + id).val('');

                showAlert('Task successfully added!', 'alert-success');
            },
            error: function (data) {
                if (data.status === 400) {
                    let htmlMessage = '';
                    $.each(data.responseJSON.errors.fields, function(field, message) {
                        htmlMessage = htmlMessage + `<b>${field}</b>: ${message}<br>`;
                    });

                    showAlert(htmlMessage, 'alert-warning');
                } else {
                    showAlert('Error, please try again later...', 'alert-danger');
                }
            }
        });
    });

    // Delete task
    $('.project-card-body').on('click', '.task-list li div .btn-delete-task', function () {
        let id = $(this).data('id');

        $.ajax({
            url: '/api/task/' + id,
            method: 'DELETE',
            success: function () {
                let taskList = $('.task-' + id).parent();
                let cardBody = taskList.parent();
                $('.task-' + id).remove();

                if (taskList.children().length === 0) {
                    cardBody.empty();
                    cardBody.append(`
                        <div class="text-center task-no">
                            <p class="mt-4">No tasks...</p>
                        </div>
                    `);
                }

                showAlert('Task successfully deleted!', 'alert-success');
            },
            error: function () {
                showAlert('Error, please try again later...', 'alert-danger');
            }
        });
    });

    // Check/uncheck task
    $('.project-card-body').on('click', '.task-list li .task-checkbox', function () {
        let id = $(this).data('id');

        $('.task-text-' + id).toggleClass('task-checked');
        $.ajax({
            url: `/api/task/${id}/completion`,
            method: 'POST',
            success: function (data) {
                let completion = data.completed ? 'checked' : 'unchecked';
                showAlert(`Task successfully ${completion}!`, 'alert-success');
            },
            error: function () {
                showAlert('Error, please try again later...', 'alert-danger');
            }
        });
    });

    // Edit task
    $('.project-card-body').on('click', '.task-list li div .btn-save-task', function () {
        let id = $(this).data('id');
        let text = $('.task-input-' + id).val();

        $.ajax({
            url: '/api/task/' + id,
            method: 'POST',
            data: JSON.stringify({
                text: text,
            }),
            success: function (data) {
                $('.btn-save-task-' + id).toggleClass('d-none');
                $('.btn-cancel-task-' + id).toggleClass('d-none');
                $('.btn-sort-task-' + id).toggleClass('d-none');
                $('.btn-edit-task-' + id).toggleClass('d-none');
                $('.btn-delete-task-' + id).toggleClass('d-none');
                $('.task-checkbox-' + id).toggleClass('d-none');
                $('.task-text-' + id).toggleClass('d-none');
                $('.task-input-' + id).toggleClass('d-none');
                $('.task-text-' + id).text(data.task.text);

                showAlert('Task successfully edited!', 'alert-success');
            },
            error: function (data) {
                if (data.status === 400) {
                    let htmlMessage = '';
                    $.each(data.responseJSON.errors.fields, function(field, message) {
                        htmlMessage = htmlMessage + `<b>${field}</b>: ${message}<br>`;
                    });

                    showAlert(htmlMessage, 'alert-warning');
                } else {
                    showAlert('Error, please try again later...', 'alert-danger');
                }
            }
        });
    });
})

function showAlert(message, type) {
    $('.alert .alert-content').empty();
    $('.alert .alert-content').html(message);
    $('.alert').removeClass('alert-success');
    $('.alert').removeClass('alert-warning');
    $('.alert').removeClass('alert-danger');
    $('.alert').addClass(type);
    $('.alert').addClass('show');

    setTimeout(function () {
        $('.alert').removeClass('show');
    }, 3000);
}

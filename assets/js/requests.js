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
                        <input class="task-checkbox" type="checkbox" data-id="${data.task.id}">
                        <span class="task-text task-text-${data.task.id}">${text}</span>
                        <div class="list-group-actions float-right">
                            <a href="#"><i class="fas fa-sort"></i></a>
                            <a href="#"><i class="fas fa-pen"></i></a>
                            <a class="btn-delete-task" href="#" data-id="${data.task.id}"><i class="fas fa-trash-alt"></i></a>
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
})

function showAlert(message, type) {
    $('.alert .alert-content').empty();
    $('.alert .alert-content').html(message);
    $('.alert').removeClass('alert-success');
    $('.alert').removeClass('alert-warning');
    $('.alert').removeClass('alert-danger');
    $('.alert').addClass(type);
    $('.alert').addClass('show');
}

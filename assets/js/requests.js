import $ from 'jquery';
import Sortable from "sortablejs";

$(document).ready(function () {
    // Add task
    $('.main-container').on('click', '.btn-add-task', function () {
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
                let date = new Date(data.task.deadline);
                let deadline = date.getFullYear() + '-' + ('0' + (date.getMonth()+1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);

                if ($('.task-list-' + id).length === 0) {
                    $('.project-card-body-' + id).empty();
                    $('.project-card-body-' + id).append(`<ul class="list-group task-list task-list-${id}"></ul>`);

                    Sortable.create($('.task-list-' + id).get(0), {
                        handle: '.fa-sort',
                        animation: 150
                    });
                }

                $('.task-list-' + id).append(`    
                    <li class="list-group-item list-group-item-action task task-${data.task.id}" data-id="${data.task.id}">
                        <div class="row">
                            <div class="col-10">
                                <input class="task-checkbox task-checkbox-${data.task.id}" type="checkbox" data-id="${data.task.id}">
                                <span class="task-text task-text-${data.task.id}">${text}</span>
                                <input class="task-input task-input-${data.task.id} d-none" type="text">
                                <div class="float-right text-right">
                                    <input class="datepicker task-datepicker-${data.task.id} d-none" type="date" value="${deadline}">
                                    <span class="task-deadline task-deadline-${data.task.id} pull-right">${data.task.deadline}</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="list-group-actions float-right text-right">
                                    <a class="btn-save-task btn-save-task-${data.task.id} d-none" href="#" data-id="${data.task.id}"><i class="fas fa-save"></i></a>
                                    <a class="btn-cancel-task btn-cancel-task-${data.task.id} d-none" href="#" data-id="${data.task.id}"><i class="fas fa-times"></i></a>
                                    <a class="btn-sort-task btn-sort-task btn-sort-task-${data.task.id}" href="#"><i class="fas fa-sort"></i></a>
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
                    showAlert(getErrorsFromResponse(data), 'alert-warning');
                } else {
                    showAlert('Error, please try again later...', 'alert-danger');
                }
            }
        });
    });

    // Delete task
    $('.main-container').on('click', '.btn-delete-task', function () {
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
    $('.main-container').on('click', '.task-checkbox', function () {
        let id = $(this).data('id');

        $('.task-text-' + id).toggleClass('task-checked');
        $('.task-deadline-' + id).toggleClass('task-checked');
        $.ajax({
            url: `/api/task/${id}/completion`,
            method: 'PUT',
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
    $('.main-container').on('click', '.btn-save-task', function () {
        let id = $(this).data('id');
        let text = $('.task-input-' + id).val();
        let deadline = $('.task-datepicker-' + id).val();

        $.ajax({
            url: '/api/task/' + id,
            method: 'PUT',
            data: JSON.stringify({
                text: text,
                deadline: deadline,
            }),
            success: function (data) {
                let deadline = new Date(data.task.deadline);
                deadline = deadline.getFullYear() + '-' + ('0' + (deadline.getMonth()+1)).slice(-2) + '-' + ('0' + deadline.getDate()).slice(-2);
                let currentDate = new Date();
                currentDate = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth()+1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2);

                $('.btn-save-task-' + id).toggleClass('d-none');
                $('.btn-cancel-task-' + id).toggleClass('d-none');
                $('.btn-sort-task-' + id).toggleClass('d-none');
                $('.btn-edit-task-' + id).toggleClass('d-none');
                $('.btn-delete-task-' + id).toggleClass('d-none');
                $('.task-checkbox-' + id).toggleClass('d-none');
                $('.task-text-' + id).toggleClass('d-none');
                $('.task-deadline-' + id).toggleClass('d-none');
                $('.task-input-' + id).toggleClass('d-none');
                $('.task-datepicker-' + id).toggleClass('d-none');
                $('.task-text-' + id).text(data.task.text);
                $('.task-deadline-' + id).text(data.task.deadline);
                $('.task-datepicker-' + id).val(deadline);

                if (deadline === currentDate) {
                    $('.task-deadline-' + id).addClass('text-danger');
                } else {
                    $('.task-deadline-' + id).removeClass('text-danger');
                }

                showAlert('Task successfully edited!', 'alert-success');
            },
            error: function (data) {
                if (data.status === 400) {
                    showAlert(getErrorsFromResponse(data), 'alert-warning');
                } else {
                    showAlert('Error, please try again later...', 'alert-danger');
                }
            }
        });
    });

    // Sort task
    $('.main-container').on('dragend', '.task', function () {
        let taskPositions = [];

        $.each($(this).parent().children(), function (index, element) {
            taskPositions.push($(element).data('id'));
        });

        $.ajax({
            url: '/api/task/sort',
            method: 'POST',
            data: JSON.stringify({
                tasks: taskPositions,
            }),
            error: function (data) {
                showAlert('Error, please try again later...', 'alert-danger');
            }
        });
    });

    // Create project
    $('.btn-add-project').on('click', function () {
        let name = $('.add-project').val();

        $.ajax({
            url: '/api/project',
            method: 'POST',
            data: JSON.stringify({
                name: name,
            }),
            success: function (data) {
                if ($('.main-row').length === 0) {
                    $('.main-container').empty();
                    $('.main-container').append('<div class="row main-row"></div>');
                }

                $('.main-row').append(`    
                    <div class="col-4 project-${data.project.id}">
                        <div class="card project-card">
                            <div class="card-header card-caption">
                                <div class="row">
                                    <div class="col-10">
                                        <span class="project-name project-name-${data.project.id}"><i class="far fa-list-alt"></i>${data.project.name}</span>
                                        <input class="project-input project-input-${data.project.id} d-none" type="text">
                                    </div>
                                    <div class="col float-right text-right">
                                        <a class="btn-edit-project btn-edit-project-${data.project.id}" href="#" data-id="${data.project.id}"><i class="fas fa-pen"></i></a>
                                        <a class="btn-delete-project btn-delete-project-${data.project.id}" href="#" data-id="${data.project.id}"><i class="fas fa-trash-alt"></i></a>
                                        <a class="btn-save-project btn-save-project-${data.project.id} d-none" href="#" data-id="${data.project.id}"><i class="fas fa-save"></i></a>
                                        <a class="btn-cancel-project btn-cancel-project-${data.project.id} d-none" href="#" data-id="${data.project.id}"><i class="fas fa-times"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header card-add-task">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control add-task-input-${data.project.id}" placeholder="Start typing here to create a task...">
                                    <div class="input-group-append">
                                        <button class="btn btn-success btn-add-task" type="button" data-id="${data.project.id}">Add task</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-black project-card-body project-card-body-${data.project.id}">
                                <div class="text-center task-no">
                                    <p class="mt-4">No tasks...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

                $('.add-project').val('');
                $('#add-project-modal .modal-dialog .modal-content .modal-header .close').trigger('click');

                showAlert('Project successfully created!', 'alert-success');
            },
            error: function (data) {
                if (data.status === 400) {
                    showAlert(getErrorsFromResponse(data), 'alert-warning');
                } else {
                    showAlert('Error, please try again later...', 'alert-danger');
                }
            }
        });
    });

    // Delete project
    $('.main-container').on('click', '.btn-delete-project', function () {
        let id = $(this).data('id');

        $.ajax({
            url: '/api/project/' + id,
            method: 'DELETE',
            success: function () {
                $('.project-' + id).remove();

                if ($('.main-row').children().length === 0) {
                    $('.main-container').empty();
                    $('.main-container').append(`
                        <div class="row justify-content-md-center h-100 align-items-center">
                            <div class="col-3 text-center no-project">
                                <p>No projects...</p>
                            </div>
                        </div>
                    `);
                }

                showAlert('Project successfully deleted!', 'alert-success');
            },
            error: function () {
                showAlert('Error, please try again later...', 'alert-danger');
            }
        });
    });

    // Edit project
    $('.main-container').on('click', '.btn-save-project', function () {
        let id = $(this).data('id');
        let name = $('.project-input-' + id).val();

        $.ajax({
            url: '/api/project/' + id,
            method: 'PUT',
            data: JSON.stringify({
                name: name,
            }),
            success: function (data) {
                $('.btn-cancel-project-' + id).toggleClass('d-none');
                $('.btn-save-project-' + id).toggleClass('d-none');
                $('.btn-edit-project-' + id).toggleClass('d-none');
                $('.btn-delete-project-' + id).toggleClass('d-none');
                $('.project-input-' + id).toggleClass('d-none');
                $('.project-name-' + id).toggleClass('d-none');
                $('.project-name-' + id).html('<i class="far fa-list-alt"></i>' + data.project.name);

                showAlert('Project successfully edited!', 'alert-success');
            },
            error: function (data) {
                if (data.status === 400) {
                    showAlert(getErrorsFromResponse(data), 'alert-warning');
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

function getErrorsFromResponse(data) {
    let htmlMessage = '';
    $.each(data.responseJSON.errors.fields, function(field, message) {
        htmlMessage = htmlMessage + `<b>${field}</b>: ${message}<br>`;
    });

    return htmlMessage;
}

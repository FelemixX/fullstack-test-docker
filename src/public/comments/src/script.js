$(document).ready(function () {

    let currentPage = 1;
    let currentSort = 'date';
    let currentDirection = 'asc';

    $('#comment_form').on("submit", function (e) {
        e.preventDefault();
        addNewComment();
    });

    $('#pagination').on('click', 'button', function (e) {
        e.preventDefault();
        currentPage = $(this).data('page');
        updateList();
    });

    $('#comment_list').on('click', '.delete-comment', function () {
        let deleteId = $(this).data('id');
        deleteComment(deleteId);
    });

    $('#sort_field').on('change', function () {
        currentSort = $(this).val();
        updateList();
    });

    $('#sort_direction').on('change', function () {
        currentDirection = $(this).val();
        updateList();
    });

    function deleteComment(deleteId) {
        $.ajax({
            url: '/',
            type: 'DELETE',
            data: {
                id: deleteId,
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.trace([jqXHR, textStatus, errorThrown]);
            },
            success: function () {
                updateList();
            }
        });
    }

    function updateList() {
        $('#error').html('');
        error = '';
        $.ajax({
            url: '/',
            type: 'GET',
            data: {
                page: currentPage,
                sort: currentSort,
                direction: currentDirection
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.trace([jqXHR, textStatus, errorThrown]);
            },
            success: function (data) {
                let elements = '';
                if (data.comments.length == 0) {
                    if (currentPage == 1) {
                        $('#comment_list').html('<h4 class="text-center">Ничего не найдено</h4>');
                        $('#pagination').html('');
                        return;
                    }

                    while (currentPage != 1) {
                        currentPage--;
                        updateList();
                    }
                } else {
                    for (let key in data.comments) {
                        const element = data.comments[key];
                        elements += '<div class="card mb-4 shadow-sm">' + '<div class="card-body">' + '<div class="d-flex justify-content-between">' + '<p>' + element.text + '</p>' + '<button type="button" class="close delete-comment" aria-label="Close" data-id=' + key + '>' + '<span aria-hidden="true">×</span>' + '</button>' + '</div>' + '<div class="d-flex justify-content-between">' + '<div class="d-flex flex-row align-items-center">' + '<p class="small mb-0 ms-2">' + element.name + '</p>' + '</div>' + '<div class="d-flex flex-row align-items-center">' + '<p class="small text-muted mb-0">' + element.date + '</p>' + '</div>' + '</div>' + '</div>' + '</div>';
                    }
                    $('#comment_list').html(elements);

                    let pagination = '<ul class="pagination justify-content-center">';
                    data.pages.forEach(function (pageNum) {
                        pagination += '<li class="page-item ' + (pageNum === currentPage ? 'active' : '') + '">' + '<button class="page-link" data-page="' + pageNum + '">' + pageNum + '</button>' + '</li>';
                    });

                    pagination += '</ul>';
                    $('#pagination').html(pagination);
                }
            }
        });
    }

    function addNewComment() {
        let name = $('#user_email').val();
        let text = $('#user_comment').val();
        $.ajax({
            url: '/',
            type: 'POST',
            data: {
                name: name,
                text: text,
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.trace([jqXHR, textStatus, errorThrown]);
            },
            success: function (data) {
                if (data.error !== '') {
                    $('#error').html('<div class="alert alert-danger" role="alert">' + data.error + '</div>');
                    return;
                }

                currentPage = 1;
                updateList();
            }
        });

        $('#user_email').val('');
        $('#user_comment').val('');
    };
});

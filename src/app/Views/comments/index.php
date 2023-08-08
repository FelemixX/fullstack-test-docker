<?php /** @var array $comments */ ?>
<?php /** @var array $pages */ ?>

    <div class="col-4 col-sm-5 col-md-6 col-lg-6 col-xl-6">
        <div class="card shadow-0 border bg-light">
            <div class="card-body p-4">
                <div id="error"></div>
                <div class="d-flex justify-content-between my-3">
                    <div class="form-group">
                        <label for="sort_field" class="mb-2">Сортировать:</label>
                        <select class="form-control" name="sort_field" id="sort_field">
                            <option value="date">Дата публикации</option>
                            <option value="id">ID</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sort_direction" class="mb-2">Направление:</label>
                        <select class="form-control" name="sort_direction" id="sort_direction">
                            <option value="asc">По возрастанию</option>
                            <option value="desc">По убыванию</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div id="comment_list">
                        <?php if (!empty($comments)): ?>
                            <?php foreach ($comments as $comment): ?>
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p>
                                                <?= $comment['text'] ?>
                                            </p>
                                            <button type="button" class="close delete-comment" aria-label="Close" data-id="<?= $comment['id'] ?>">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex flex-row align-items-center">
                                                <p class="small mb-0 ms-2">
                                                    <?= $comment['name'] ?>
                                                </p>
                                            </div>
                                            <div class="d-flex flex-row align-items-center">
                                                <p class="small text-muted mb-0">
                                                    <?= $comment['date'] ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php else: ?>
                            <div class="text-center mb-4 text-uppercase">
                                <strong>
                                    Никто не оставил комментариев :(
                                </strong>
                            </div>
                        <?php endif ?>
                    </div>
                    <nav id="pagination">
                        <?php if (!empty($comments) && !empty($pages)): ?>
                            <ul class="pagination justify-content-center">
                                <?php foreach ($pages as $pageIndex): ?>
                                    <li class="page-item <?= $pageIndex == 1 ? 'active' : ''?>">
                                        <button class="page-link" data-page="<?= $pageIndex ?>">
                                            <?= $pageIndex ?>
                                        </button>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif; ?>
                    </nav>
                    <form id="comment_form" class="mb-4">
                        <div class="mb-3">
                            <label>
                                <strong>
                                    Ваша почта:
                                </strong>
                            </label>
                            <input class="form-control" minlength="5" type="email" name="user_email" pattern="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$" id="user_email" placeholder="your@email.ru" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" minlength="2" id="user_comment" name="user_comment" rows="3" placeholder="Ваш комментарий" required></textarea>
                        </div>
                        <button id="send_comment" type="submit" class="btn btn-primary">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?= script_tag(base_url('comments/dist/script.js')) ?>
<?php echo '<script>console.log(' . json_encode($comments) . ');</script>'; //TODO: DELETE IV_LOGGING?>

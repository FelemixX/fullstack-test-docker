<?php

namespace App\Controllers;

use App\Controllers\AbstractControllers\AbstractController;
use App\Models\CommentsModel;

class CommentsController extends AbstractControllers\AbstractController
{
    public const TIME_FORMAT = 'Y-m-d H:i:s';

    protected static array $validationRules = [
        'name' => [
            'required',
            'valid_email'
        ],
        'text' => [
            'required',
            'min_length[1]',
            'max_length[2000]'
        ],
    ];

    /**
     * @return string
     */
    public function index()
    {
        helper('html');

        $page = $this->request->getVar('page');
        $sortField = $this->request->getVar('sort');
        $sortDirection = $this->request->getVar('direction');

        if ($page && $sortField && $sortDirection) {
            $comments = $this->read($page, $sortField, $sortDirection);

            return $this->response->setJSON($comments);
        } else {
            $comments = $this->read();
        }

        return view('templates/main/header', ['title' => 'Комментарии']) .
            view('comments/index', ['comments' => $comments['comments'], 'pages' => $comments['pages']]) .
            view('templates/main/footer');
    }

    /**
     * @throws \Exception
     */
    public function create()
    {
        $this->checkIsAjax();

        if (!$this->validate(static::$validationRules)) {
            $response = [
                'error' => $this->validator->getErrors(),
            ];
        } else {
            helper('date');

            $request = $this->request->getPost([
                    'name',
                    'text'
                ]
            );
            $model = static::getModel();

            $model->saveComment([
                'name' => $request['name'],
                'text' => $request['text'],
                'date' => date(static::TIME_FORMAT),
            ]);

            $response = [
                'error' => '',
            ];
        }

        return $this->response->setJSON($response);
    }

    /**
     * @return array
     */
        public function read(int $page = 1, string $sortField = 'date', string $sortDirection = 'asc'): array
    {
        $model = static::getModel();
        $commentsData = $model->getComments($page, $sortField, $sortDirection);
        if (empty($commentsData['comments'])) {
            return [];
        }

        $pages = $commentsData['pages'];
        $comments = [];
        foreach ($commentsData['comments'] as $comment) {
            $comments[$comment->id]['name'] = $comment->name;
            $comments[$comment->id]['text'] = $comment->text;
            $comments[$comment->id]['date'] = $comment->date;
        }

        return [
            'comments' => $comments,
            'pages' => $pages,
        ];
    }

    public function update()
    {
        throw new \Exception('Method is not implemented');
    }

    /**
     * @throws \Exception
     */
    public function delete()
    {
        $this->checkIsAjax();
        $model = static::getModel();
        $request = $this->request->getRawInput();
        if (!intval($request['id'])) {
            return false;
        }

        echo $model->deleteComment(intval($request['id']));
    }

    /**
     * @return mixed
     */
    protected static function getModel()
    {
        return model(CommentsModel::class);
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function checkIsAjax()
    {
        if (!$this->request->isAJAX()) {
            throw new \Exception('Not allowed');
        }
    }
}

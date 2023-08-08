<?php

namespace App\Controllers;

use App\Controllers\AbstractControllers\AbstractController;
use App\Models\CommentsModel;
use CodeIgniter\HTTP\ResponseInterface;

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
        $comments = $this->read();

        return view(
            'comments/index',
            [
                'comments' => $comments
            ]
        );
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
    public function read(): array
    {
        $model = static::getModel();
        $commentsData = $model->getComments();

        if (empty($commentsData)) {
            return [];
        }

        $comments = [];
        foreach ($commentsData as $comment) {
            $comments[$comment->id]['name'] = $comment->name;
            $comments[$comment->id]['text'] = $comment->text;
            $comments[$comment->id]['date'] = $comment->date;
        }

        return $comments;
    }

    public function update()
    {
        throw new \Exception('Method is not implemented');
    }

    public function delete(): bool
    {
        $this->checkIsAjax();
        $model = static::getModel();
        $id = $this->request->getPost([
            'id',
        ]);

        return $model->deleteComment($id);
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

<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentsModel extends Model
{
    protected static $tableName = 'comments';

    /**
     * @param int $page
     * @param int $limit
     * @param string $sortField
     * @param string $sortDirection
     * @return array
     */
    public function getComments(int $page = 1, string $sortField = 'date', string $sortDirection = 'asc', int $limit = 3): array
    {
        if ($page < 1) {
            return [];
        }

        $offset = ($page - 1) * $limit;
        $selectFields = [
            'id',
            'name',
            'text',
            'date',
        ];

        $builder = $this->initBuilder();
        $comments = $builder->select($selectFields)
            ->orderBy($sortField, $sortDirection)
            ->get($limit, $offset)
            ->getResult();

        $totalComments = $builder->countAllResults();
        $totalPages = ceil($totalComments / $limit);
        $startPage = max(1, $page - 2);
        $endPage = min($totalPages, $page + 2);
        $pages = range($startPage, $endPage);

        return [
            'comments' => $comments,
            'pages' => $pages,
        ];
    }

    public function saveComment(array $data): bool
    {
        $builder = $this->initBuilder();
        try {
            $builder->insert(
                [
                    'name' => $data['name'],
                    'text' => $data['text'],
                    'date' => $data['date'],
                ]
            );
        } catch (CodeIgniter\Database\Exceptions\DatabaseException $databaseException) {
            return false;
        }

        return true;
    }

    public function deleteComment(int $id)
    {
        $builder = $this->initBuilder();
        try {
            $builder->where('id', $id);
            $builder->delete();
        } catch (CodeIgniter\Database\Exceptions\DatabaseException $databaseException) {
            return false;
        }

    }

    protected function initBuilder(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->db->table(static::$tableName);
    }
}

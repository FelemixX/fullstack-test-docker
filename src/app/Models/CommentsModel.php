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
    public function getComments(int $page = 1, int $limit = 3, string $sortField = 'date', string $sortDirection = 'asc'): array
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

        return $builder->select($selectFields)
            ->orderBy($sortField, $sortDirection)
            ->get($limit, $offset)
            ->getResult();
    }

    public function saveComment(array $data): bool
    {
        $this->initBuilder();
        try {
            $this->doInsert($data);
        } catch (CodeIgniter\Database\Exceptions\DatabaseException $databaseException) {
            return false;
        }

        return true;
    }

    public function deleteComment(int $id): bool
    {
        $this->initBuilder();
        try {
            $this->doDelete();
            return true;
        } catch (CodeIgniter\Database\Exceptions\DatabaseException $databaseException) {
            return false;
        }

    }

    protected function initBuilder(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->db->table(static::$tableName);
    }
}

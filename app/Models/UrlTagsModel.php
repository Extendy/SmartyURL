<?php

namespace App\Models;

class UrlTagsModel extends BaseModel
{
    protected $DBGroup          = 'default';
    protected $primaryKey       = 'tag_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function initialize(): void
    {
        parent::initialize();

        $this->table = $this->dbtables['urltags'];
    }

    public function getTagsCloud($userId, $shared = false, ?int $limit = null)
    {
        // Define the condition to filter results where tag_user_id is equal to $userId
        if (! $shared) {
            $this->where('tag_user_id', $userId);
        }

        // Set a limit if provided
        if ($limit !== null) {
            $this->limit($limit);
        }

        $this->select('tag_id, tag_name');

        // Retrieve the results based on the condition
        return $this->findAll();
    }
}

<?php

namespace App\Models;

class UrlModel extends BaseModel
{
    protected $DBGroup          = 'default';
    protected $primaryKey       = 'url_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'url_identifier',
        'url_user_id',
        'url_title',
        'url_targeturl',
        'url_conditions',
    ];

    // Dates
    protected $useTimestamps = true;
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

        $this->table = $this->dbtables['urls'];
    }

    public function InsertURL()
    {
    }

    public function increaseHitsCount($urlId)
    {
        $builder = $this->builder();
        $builder->where('url_id', $urlId)
            ->set('url_hitscounter', 'url_hitscounter + 1', false)
            ->update();
    }

    /**
     * This function gets the URL list for a given user ID in $userIds, which can be a single user ID or an array of user IDs.
     * $returnType can be:
     * - 'data' to return data as an array
     * - 'count' to return the row count as an integer without the start and limit
     */
    public function getUrlsForUser(int|array|null $userIds = null, int|null $start = null, int|null $limit = null, ?string $search_string = null, string $returnType = 'data'): array|int
    {
        $builder = $this->builder();

        // Check if $userIds is an array or a single value
        if (isset($userIds)) {
            if (is_array($userIds)) {
                // If it's an array, use WHERE IN to filter by multiple user IDs
                $builder->whereIn('url_user_id', $userIds);
            } else {
                // If it's a single value, use a simple WHERE to filter by that user ID
                $builder->where('url_user_id', $userIds);
            }
        }

        if ($search_string !== null) {
            $builder->groupStart()
                ->like('url_identifier', $search_string)
                ->orLike('url_id', $search_string)
                ->orLike('url_title', $search_string)
                ->orLike('url_targeturl', $search_string)
                ->groupEnd();
        }

        if ($returnType === 'count') {
            // Return the count of all rows without the limit and start
            return $builder->countAllResults();
        }

        if ($start !== null) {
            $builder->limit($limit, $start);
        } elseif ($limit !== null) {
            $builder->limit($limit);
        }

        // Retrieve the records
        return $builder->get()->getResult();
    }
}

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
        'url_shared',
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
    public function getUrlsForUser(int|array|null $userIds = null, int|null $start = null, int|null $limit = null, ?string $search_string = null, string $orderBy = 'url_id', string $orderDirection = 'asc', string $returnType = 'data'): array|int
    {
        $builder = $this->builder();

        // Check if shared URLs should be included
        $sharedUrlFeatureEnabled = setting('Smartyurl.url_can_be_shared_between_users');

        // Check if $userIds is an array or a single value
        if (isset($userIds)) {
            $builder->groupStart();

            if (is_array($userIds)) {
                // If it's an array, use WHERE IN to filter by multiple user IDs
                $builder->whereIn('url_user_id', $userIds);
            } else {
                // If it's a single value, use a simple WHERE to filter by that user ID
                $builder->where('url_user_id', $userIds);
            }

            if ($sharedUrlFeatureEnabled) {
                $builder->orWhere('url_shared', 1);
            }

            $builder->groupEnd();
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

        if ($orderBy !== null) {
            // Add the ORDER BY clause
            $builder->orderBy($orderBy, $orderDirection);
        }

        if ($start !== null) {
            $builder->limit($limit, $start);
        } elseif ($limit !== null) {
            $builder->limit($limit);
        }

        // Retrieve the records
        return $builder->get()->getResult();
    }

    public function deleteUrlById(int|array $urlId)
    {
        if (is_array($urlId)) {
            // If $urlId is an array, delete multiple records
            $this->whereIn('url_id', $urlId)->delete();
        } else {
            // If $urlId is a single value, delete a single record
            $this->where('url_id', $urlId)->delete();
        }

        // Check the affected rows to determine if the deletion was successful
        return $this->db->affectedRows() > 0;
    }

    /**
     * Get created Url count for all users, or for a specific user if $userId is provided,
     * within a specified create date range (created_at)
     *
     * @param int|null $userId
     * @param string   $dateRange ('all', 'this_month', 'today')
     *
     * @return int
     */
    public function getUrlCount($userId = null, $dateRange = 'all')
    {
        if ($userId !== null) {
            // If $urlUserId is an array, use whereIn
            if (is_array($userId)) {
                $this->whereIn('url_user_id', $userId);
            } else {
                // If $urlUserId is a single ID, use where
                $this->where('url_user_id', $userId);
            }
        }

        if ($dateRange === 'this_month') {
            $this->where('MONTH(created_at)', date('m'))
                ->where('YEAR(created_at)', date('Y'));
        } elseif ($dateRange === 'today') {
            $this->where('DATE(created_at)', date('Y-m-d'));
        }

        return $this->countAllResults();
    }
}

<?php

namespace App\Models;

class UrlTagsDataModel extends BaseModel
{
    protected $DBGroup          = 'default';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'url_id',
        'tag_id',
    ];

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

        $this->table = $this->dbtables['urltagsdata'];
    }

    /**
     * This function get the url tags for $urlid
     *
     * @param mixed $urlid
     *
     * @return array
     */
    public function getUrlTags($urlid)
    {
        $this->where('url_id', $urlid);
        $this->select('tag_id');

        return $this->findAll();
    }

    public function delUrlTags($urlId)
    {
        return $this->where('url_id', $urlId)->delete();
    }

    public function getUrlInfoForTagId(
        int|array $tagId,
        int|array|null $userIds = null,
        ?string $search_string = null,
        $start = null,
        $limit = null,
        $orderByColumn = 'url_id',
        $orderByDirection = 'desc',
        $returnType = 'data'
    ) {
        $builder = $this->builder();
        $builder->select('urls.*'); // Select all columns from 'urls'
        $builder->join('urls', 'urls.url_id = urltagsdata.url_id');

        if (is_array($tagId)) {
            // If $tagId is an array, use WHERE IN to filter by multiple tag IDs
            $builder->whereIn('urltagsdata.tag_id', $tagId);
        } else {
            // If $tagId is a single value, use a simple WHERE to filter by that tag ID
            $builder->where('urltagsdata.tag_id', $tagId);
        }

        if ($userIds !== null) {
            if (is_array($userIds)) {
                // If $userIds is an array, use WHERE IN to filter by multiple user IDs
                $builder->whereIn('urls.url_user_id', $userIds);
            } else {
                // If $userIds is a single value, use a simple WHERE to filter by that user ID
                $builder->where('urls.url_user_id', $userIds);
            }
        }

        if ($search_string !== null) {
            // Add search conditions for url_identifier, url_user_id, url_title, and url_targeturl
            $builder->groupStart()
                ->like('urls.url_identifier', $search_string)
                ->orLike('urls.url_user_id', $search_string)
                ->orLike('urls.url_title', $search_string)
                ->orLike('urls.url_targeturl', $search_string)
                ->groupEnd();
        }

        if ($start !== null) {
            $builder->limit($limit, $start);
        } elseif ($limit !== null) {
            $builder->limit($limit);
        }

        if ($orderByColumn !== null) {
            $builder->orderBy($orderByColumn, $orderByDirection);
        }

        if ($returnType === 'count') {
            return $builder->countAllResults();
        }

        $query = $builder->get();

        if ($query->getResult()) {
            return $query->getResult(); // Returns an array of URL information
        }

        return null; // or any default value to indicate no matching records
    }
}

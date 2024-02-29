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
    protected $allowedFields    = [
        'tag_name',
        'tag_user_id',
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

    /**
     * This function return Tag info as array [tag_id , tag_name] for the given $tagIDs which can be one or array of tag ids
     *
     * @param array|int $tagIDs
     *
     * @return mixed
     */
    public function getTagInfoById($tagIDs)
    {
        $this->select('tag_id, tag_name');

        if (is_array($tagIDs)) {
            $query = $this->whereIn('tag_id', $tagIDs)->get();
        } else {
            $query = $this->where('tag_id', $tagIDs)->get();
        }

        return $query->getResult();
    }

    /**
     * This function try to find tag and return how many tags found, it is only return nmbers
     *
     * @return int
     */
    public function findTagNo(string $tag, int $userId, bool $shared = false)
    {
        // Define the condition to filter results where tag_user_id is equal to $userId
        if (! $shared) {
            $this->where('tag_user_id', $userId)->where('tag_name', $tag);
        } else {
            $this->where('tag_name', $tag);
        }

        return $this->countAllResults();
    }

    /**
     * This function return tags cloud with url count for each tag
     */
    public function getTagsWithUrlCount()
    {
        return $this->db->table($this->table)
            ->select('urltags.*, COUNT(urltagsdata.url_id) AS url_count, users.username AS creator_username')
            ->join('urltagsdata', 'urltags.tag_id = urltagsdata.tag_id', 'left')
            ->join('users', 'urltags.tag_user_id = users.id', 'left')
            ->groupBy('urltags.tag_id')
            ->get()
            ->getResultArray();
    }
}

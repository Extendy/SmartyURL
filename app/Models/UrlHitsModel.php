<?php

namespace App\Models;

class UrlHitsModel extends BaseModel
{
    protected $DBGroup          = 'default';
    protected $primaryKey       = 'urlhit_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'urlhit_urlid',
        'urlhit_at',
        'urlhit_ip',
        'urlhit_country',
        'urlhit_visitordevice',
        'urlhit_useragent',
        'urlhit_finaltarget',
        'urlhit_data',
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

        $this->table = $this->dbtables['urlhits'];
    }

    /**
     * get the last 25 hits for URL
     *
     * @param mixed $UrlId
     *
     * @return mixed
     */
    public function getLast25Hits($UrlId)
    {
        // Using Query Builder to retrieve the last 25 rows
        return $this->where('urlhit_urlid', $UrlId)
            ->orderBy('urlhit_id', 'DESC')
            ->limit(25)
            ->get()
            ->getResult();
    }

    /**
     * This function gets the URL visit list for a given $urlId which can be a single Url ID or an array of Url IDs.
     * $returnType can be:
     * - true to return data as an array
     * - false to return the row count as an integer without the start and limit
     */
    public function getHitsByUrlId(int|array $urlId, int|null $start = null, int|null $length = null, string $orderBy = 'urlhit_urlid', string $orderDirection = 'DESC', bool $returnData = true)
    {
        $builder = $this->builder();

        $builder->orderBy($orderBy, $orderDirection);

        if (is_array($urlId)) {
            $builder->whereIn('urlhit_urlid', $urlId);
        } else {
            $builder->where('urlhit_urlid', $urlId);
        }

        if ($start !== null && $length !== null) {
            $builder->limit($length, $start);
        }

        if ($returnData) {
            return $builder->get()->getResult();
        }

        return $builder->countAllResults();
    }
}

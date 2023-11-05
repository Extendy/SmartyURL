<?php

namespace App\Models;

class UrlHitsModel extends BaseModel
{
    protected $DBGroup          = 'default';
    protected $primaryKey       = 'id';
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
}

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
     * This function gets the URL visit list for a given $urlId or $userId which can be a single Url ID or an array of Url IDs.
     * $returnType can be:
     * - true to return data as an array
     * - false to return the row count as an integer without the start and limit
     */
    public function getHitsByUrlId(int|array|null $urlId = null, int|array|null $userId = null, string|null $period = null, int|null $start = null, int|null $length = null, string $orderBy = 'urlhit_urlid', string $orderDirection = 'DESC', bool $returnData = true)
    {
        $builder = $this->builder();
        $builder->select('urlhits.*, urls.url_identifier'); // Select the necessary columns

        $builder->join('urls', 'urls.url_id = urlhits.urlhit_urlid'); // Join with the urls table

        $builder->orderBy($orderBy, $orderDirection);

        // Specify the period if provided
        if ($period !== null) {
            switch ($period) {
                case 'today':
                    $startOfDay = date('Y-m-d 00:00:00');
                    $endOfDay   = date('Y-m-d 23:59:59');
                    $builder->where('urlhit_at >=', $startOfDay);
                    $builder->where('urlhit_at <=', $endOfDay);
                    break;

                case 'this_month':
                    $startOfMonth = date('Y-m-01 00:00:00');
                    $endOfMonth   = date('Y-m-t 23:59:59');
                    $builder->where('urlhit_at >=', $startOfMonth);
                    $builder->where('urlhit_at <=', $endOfMonth);
                    break;
                    // Add more cases for other periods if needed
            }
        }

        if ($urlId === null) {
            // No URL ID provided, return all hits
        } else {
            if (is_array($urlId)) {
                $builder->whereIn('urlhit_urlid', $urlId);
            } else {
                $builder->where('urlhit_urlid', $urlId);
            }
        }

        // Apply user ID filter if provided
        if ($userId !== null) {
            if (is_array($userId)) {
                $builder->whereIn('urls.url_user_id', $userId);
            } else {
                $builder->where('urls.url_user_id', $userId);
            }
        }

        if ($start !== null && $length !== null) {
            $builder->limit($length, $start);
        }

        if ($returnData) {
            return $builder->get()->getResult();
        }

        return $builder->countAllResults();
    }

    public function getHitsForUserIdUrls(int|array|null $userId = null, string|null $period = null, int|null $start = null, int|null $length = null, string $orderBy = 'urlhit_urlid', string $orderDirection = 'DESC', bool $returnData = true)
    {
        $builder = $this->builder();
        $builder->select('urlhits.*, urls.url_identifier'); // Select the necessary columns

        $builder->join('urls', 'urls.url_id = urlhits.urlhit_urlid'); // Join with the urls table
        $builder->whereIn('urls.url_user_id', $userId);

        $builder->orderBy($orderBy, $orderDirection);

        // Specify the period if provided
        if ($period !== null) {
            switch ($period) {
                case 'today':
                    $startOfDay = date('Y-m-d 00:00:00');
                    $endOfDay   = date('Y-m-d 23:59:59');
                    $builder->where('urlhit_at >=', $startOfDay);
                    $builder->where('urlhit_at <=', $endOfDay);
                    break;

                case 'this_month':
                    $startOfMonth = date('Y-m-01 00:00:00');
                    $endOfMonth   = date('Y-m-t 23:59:59');
                    $builder->where('urlhit_at >=', $startOfMonth);
                    $builder->where('urlhit_at <=', $endOfMonth);
                    break;
                    // Add more cases for other periods if needed
            }
        }

        if ($userId === null) {
            // No URL ID provided, return all hits
        } else {
            if (is_array($userId)) {
                $builder->whereIn('urls.url_user_id', $userId);
            } else {
                $builder->where('urls.url_user_id', $userId);
            }
        }

        if ($start !== null && $length !== null) {
            $builder->limit($length, $start);
        }

        if ($returnData) {
            return $builder->get()->getResult();
        }

        return $builder->countAllResults();
    }

    /**
     * Get count of hits for all URLs, or for a specific user if $userId is provided,
     * within a specified date range.
     *
     * @param int|null $userId
     * @param string   $dateRange ('all', 'this_month', 'today')
     *
     * @return int
     */
    public function getCountHits($userId = null, $dateRange = 'all')
    {
        $query = $this->db->table('urlhits')
            ->join('urls', 'urls.url_id = urlhits.urlhit_urlid');

        if ($userId !== null) {
            $query->where('urls.url_user_id', $userId);
        }

        if ($dateRange === 'this_month') {
            $query->where('MONTH(urlhits.urlhit_at)', date('m'))
                ->where('YEAR(urlhits.urlhit_at)', date('Y'));
        } elseif ($dateRange === 'today') {
            $query->where('DATE(urlhits.urlhit_at)', date('Y-m-d'));
        }

        return $query->countAllResults();
    }
}

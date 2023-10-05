<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

abstract class BaseModel extends Model
{
    protected array $dbtables;

    public function __construct()
    {
        $this->smartyurlConfig = config('Smartyurl');

        if ($this->smartyurlConfig->DBGroup !== null) {
            $this->DBGroup = $this->smartyurlConfig->DBGroup;
        }

        parent::__construct();
    }

    protected function initialize(): void
    {
        $this->dbtables = $this->smartyurlConfig->dbtables;
    }
}

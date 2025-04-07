<?php

namespace app\models;

use app\config\DataBase;

class BulkUploadModel
{
    private $db;
    
    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }
}

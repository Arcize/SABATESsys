<?php
namespace app\controllers;
use app\models\BulkUploadModel;
class BulkUploadController{

    private $BulkUploadModel;
    public function __construct() {
        $this->BulkUploadModel = new BulkUploadModel();
    }
}
    
?>
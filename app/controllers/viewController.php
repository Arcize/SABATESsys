<?php

namespace app\controllers;

use app\models\ViewModel;

class ViewController extends ViewModel
{
    public function getViewController($view)
    {
        if ($view != "") {
            $answer = $this->getViewModel($view);
        } else {
            $answer = "login";
        }
        return $answer;
    }
}

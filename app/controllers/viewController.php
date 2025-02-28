<?php
include_once("app/models/viewModel.php");
class viewController extends viewModel
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

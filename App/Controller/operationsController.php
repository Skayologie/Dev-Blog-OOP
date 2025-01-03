<?php

namespace App\Controller;
require realpath(__DIR__."/../../vendor/autoload.php");
use App\Modules\Admin;
use App\Modules\CRUD;

class operationsController
{
    public static function operation($id,$operation,$table,$coll){
        if ($operation == "archive"){
            Admin::archive($id,$table,$coll);
        }
        elseif ($operation == "restore"){
            Admin::restore($id,$table,$coll);
        }
        elseif ($operation == "Delete"){
            CRUD::Delete($id,$table,$coll);
        }
    }
}
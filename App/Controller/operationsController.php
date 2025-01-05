<?php

namespace App\Controller;
require realpath(__DIR__."/../../vendor/autoload.php");
use App\Modules\Admin;
use App\Modules\CRUD;

class operationsController
{
    public static function operation($id,$operation,$table,$coll,$returnTo){
        if ($operation == "archive"){
            if (Admin::archive($id,$table,$coll)){
                header("Location:$returnTo");
                exit();
            }
        }
        elseif ($operation == "restore"){
            if (Admin::restore($id,$table,$coll)){
                header("Location:$returnTo");
                exit();
            }
        }
        elseif ($operation == "Delete"){
            if (CRUD::Delete($id,$table,$coll)){
                header("Location:$returnTo");
                exit();
            }
        }
        elseif ($operation == "accept"){
            if (CRUD::AcceptArticle($id)){
                header("Location:$returnTo");
                exit();
            }
        }
        elseif ($operation == "reject"){
            if (CRUD::AcceptArticle($id)){
                header("Location:$returnTo");
                exit();
            }
        }
    }
}
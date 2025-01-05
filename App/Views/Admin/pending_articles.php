<?php

use App\Controller\operationsController;
use App\Controller\usersController;
use App\Controller\articleController;

require __DIR__."/../../../vendor/autoload.php";

if (isset($_GET["id"]) && isset($_GET["op"])) {
    if ($_GET["op"] == "accept") {
        $id = intval($_GET["id"]) ;
        $op = $_GET["op"] ;
        operationsController::operation($id,$op,"articles"," ","Archived.php?target=articles&status=pending");
    }
    if ($_GET["op"] == "reject") {
        $id = intval($_GET["id"]) ;
        $op = $_GET["op"] ;
        operationsController::operation($id,$op,"articles"," ","Archived.php?target=articles&status=pending");
    }

}

?>
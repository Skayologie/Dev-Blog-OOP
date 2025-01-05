<?php

use App\Controller\operationsController;
use App\Controller\usersController;
use App\Controller\articleController;

require __DIR__."/../../../vendor/autoload.php";


if (isset($_GET["target"])){
    if($_GET["target"] == "articles"){
        $resultsPending = articleController::GetPendingArticles();
    }
}
else{
    header("Location:TableUsers.php");
}
?>
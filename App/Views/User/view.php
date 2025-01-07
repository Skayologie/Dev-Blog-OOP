<?php
require realpath(__DIR__."/../../../vendor/autoload.php");
use App\Modules\article;

if (isset($_GET["id"])) {
    $Articleid = intval($_GET["id"]) ;
    article::incViews($Articleid);
    header("Location:./view-article.php?id=".$_GET["id"]);
}else{
    header("Location:./index.php");
}

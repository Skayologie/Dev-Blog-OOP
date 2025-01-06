<?php
require __DIR__."/../../vendor/autoload.php";

use App\Modules\User;
session_start();
User::logout();
header("Location:login.php");


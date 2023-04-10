<?php
require_once './include/conf/const.php';
require_once './include/model/function.php';
logout();
header('Location: login.php');
exit();

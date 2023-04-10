<?php
require_once './include/conf/const.php';
require_once './include/model/function.php';
//変数初期化
$user_name = '';
$password = '';
$arr_result = [];
$data = '';
//メッセージ変数
$err_msg = [];
$message = [];
$link = db_connect();
if (isset($_SESSION['user_id'])) {
    //ログイン済みの場合、Topページへリダイレクト
    header('Location:products_list.php');
    exit();
}
if ($link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'];
        $user_name = $_POST['user_name'];
        if (isset($_POST['cookie_check']) === TRUE) {
            $cookie_check = $_POST['cookie_check'];
        } else {
            $cookie_check = '';
        }
        $result = login($password, $user_name, $cookie_check, $link, $err_msg);

        if ($result === FALSE) {
            $err_msg[] = 'ユーザーIDまたはパスワードが間違っています';
        }
    }
    if (isset($_COOKIE['cookie_check']) === TRUE) {
        $user_name = $_COOKIE['user_name'];
    }
    db_close($link);
}
include_once './include/view/login_view.php';

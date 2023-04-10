<?php
require_once './include/conf/const.php';
require_once './include/model/function.php';

check_admin_session();
//変数初期化
$registered_date = '';
$arr_result = [];
//メッセージ変数
$err_msg = [];
$massage = [];
$link = db_connect();
if ($link) {
    $query = 'SELECT user_name, created_date FROM ec_users_table';
    if (!($arr_result = db_select($link, $query))) {
        $err_msg[] = 'selectクエリ失敗';
    }
    db_close($link);
} else {
    $err_msg[] = 'データベース接続失敗';
}
include_once './include/view/admin_users_view.php';

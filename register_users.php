<?php
require_once './include/conf/const.php';
require_once './include/model/function.php';

//変数初期化
$user_name = '';
$password = '';
$arr_result = [];
//メッセージ変数
$err_msg = [];
$message = [];
$link = db_connect();
if ($link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];
        // エラー処理
        if (ctype_alnum($user_name)) {
            if (mb_strlen($user_name) === 0) {
                $err_msg[] = 'ユーザー名は文字列を入力してください';
            } else if (mb_strlen($user_name) < 6) {
                $err_msg[] = 'ユーザー名は６文字以上入力してください';
            }
        } else {
            $err_msg[] = 'ユーザー名は半角英数字を入力してください';
        }
        if (ctype_alnum($password)) {
            if (is_string($password) !== TRUE) {
                $err_msg[] = 'パスワードは文字列を入力してください';
            } else if (mb_strlen($password) < 6) {
                $err_msg[] = 'パスワードは６文字以上入力してください';
            }
        } else {
            $err_msg[] = 'パスワードは半角英数字を入力してください';
        }
        //正常処理
        if (empty($err_msg)) {
            $query = "SELECT * FROM ec_users_table WHERE user_name = '" . $user_name . "'";
            if (db_select($link, $query)) {
                $err_msg[] = '入力されたユーザー名はすでに使用されています。 違うユーザー名を入力してください';
            } else {
                $password = hash('sha256', $password);
                $query = 'INSERT INTO ec_users_table(user_name, password) VALUES( "' . $user_name . '", "' . $password . '")';
                if (db_insert($link, $query)) {
                    $message[] = 'アカウントを作成を完了しました';
                } else {
                    $err_msg[] = 'INSERTクエリ失敗';
                }
            }
        }
    }
    db_close($link);
} else {
    $err_msg[] = 'データベース接続失敗';
}
include_once './include/view/register_users_view.php';

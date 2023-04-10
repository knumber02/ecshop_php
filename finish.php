<?php
require_once './include/conf/const.php';
require_once './include/model/function.php';
$user_id = check_user_session();
$total = 0;
//メッセージ変数
$err_msg = [];
$message = [];
$arr_result = [];
//データベース接続
$link = db_connect();
if ($link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $query = 'SELECT user_name FROM ec_users_table WHERE id = ' . $user_id;
        if ($result = (db_select($link, $query))) {
            $user_name = $result[0]['user_name'];
        } else {
            $err_msg[] = 'ユーザー名取得失敗';
        }
        $query = 'SELECT  ept.id, ept.name, ept.price, ept.img, ept.status, ect.amount, eps.stock
                FROM ec_products_table as ept
                JOIN ec_cart_table as ect
                ON ept.id = ect.item_id
                JOIN ec_products_stock as eps
                ON ept.id = eps.item_id
                WHERE ept.status = 1 AND ept.deleted_flg = 0  AND ect.user_id = ' . $user_id;
        $arr_result = db_select($link, $query);
        if ($arr_result === FALSE) {
            $err_msg[] = 'SELECTクエリ失敗';
        } else if (count($arr_result) === 0) {
            $err_msg[] = 'カートに商品がございません';
        } else {
            mysqli_autocommit($link, false);
            foreach ($arr_result as $value) {
                if ($value['stock'] < $value['amount']) {
                    $err_msg[] = '商品の在庫数が足りません 商品購入に失敗しました';
                }
                $total += $value['price'] * $value['amount'];
                $query = 'UPDATE ec_products_stock SET stock = stock - ' . $value['amount'] . ' WHERE item_id = ' . $value['id'];
                if (db_update($link, $query) === FALSE) {
                    $err_msg[] = '在庫テーブル在庫数更新失敗';
                }
            }
            $query = 'DELETE FROM ec_cart_table WHERE user_id = ' . $user_id;
            if ((mysqli_query($link, $query) === FALSE)) {
                $err_msg[] = 'カート情報削除失敗';
            }
            if (empty($err_msg)) {
                mysqli_commit($link);
                $message[] = 'ご購入ありがとうございました';
            } else {
                mysqli_rollback($link);
            }
        }
    }
    db_close($link);
}
include_once './include/view/finish_view.php';

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
        $sql_kind = $_POST['sql_kind'];
        $item_id = $_POST['item_id'];
        if ($sql_kind === 'change') {
            $amount = $_POST['amount'];
            if (ctype_digit($amount) && $amount > 0) {
                $query = "UPDATE ec_cart_table SET amount = " . $amount . " WHERE item_id = " . $item_id . " AND user_id = " . $user_id;
                if (db_update($link, $query) !== TRUE) {
                    $err_msg[] = 'カートテーブルINSERTクエリ失敗';
                } else {
                    $message[] = 'カートの商品の数量を変更しました';
                }
            } else {
                $err_msg[] = '数量は正の整数を入力してください';
            }
        } else if ($sql_kind === 'delete') {
            $query = "DELETE FROM ec_cart_table WHERE item_id = " . $item_id . " AND user_id = " . $user_id;
            if (mysqli_query($link, $query) !== TRUE) {
                $err_msg[] = 'カートテーブルDELETEクエリ失敗';
            } else {
                $message[] = 'カートから商品を削除しました';
            }
        }
    }
    //GET処理
    $query = 'SELECT user_name FROM ec_users_table WHERE id = ' . $user_id;
    if ($result = (db_select($link, $query))) {
        $user_name = $result[0]['user_name'];
    } else {
        $err_msg[] = 'ユーザー名取得失敗';
    }
    $query = 'SELECT  ept.id, ept.name, ept.price, ept.img, ept.status, ect.amount
                FROM ec_products_table as ept
                JOIN ec_cart_table as ect
                ON ept.id = ect.item_id
                WHERE ect.user_id = ' .  $user_id  . ' AND ept.status = 1 AND ept.deleted_flg = 0';
    $arr_result = db_select($link, $query);
    if ($arr_result === FALSE) {
        $err_msg[] = 'SELECTクエリ失敗';
    } else {
        if (count($arr_result) === 0) {
            $err_msg[] = 'カートに商品がございません';
        }
        foreach ($arr_result as $value) {
            $total += $value['price'] * $value['amount'];
        }
    };
    db_close($link);
}
include_once './include/view/cart_view.php';

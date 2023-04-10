<?php
require_once './include/conf/const.php';
require_once './include/model/function.php';
$user_id = check_user_session();
//メッセージ変数
$err_msg = [];
$massage = [];
//データベース接続
$link = db_connect();
if ($link) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $item_id = $_POST['item_id'];
        $sql_kind = $_POST['sql_kind'];
        $amount = 1;
        if ($sql_kind === 'insert_cart') {
            $query = 'SELECT amount FROM ec_cart_table WHERE item_id = ' . $item_id . " AND user_id = " . $user_id;
            if ($result = db_select($link, $query)) {
                $total_amount = $result[0]['amount'] + $amount;
                $query = "UPDATE ec_cart_table SET amount = " . $total_amount . " WHERE item_id = " . $item_id . " AND user_id = " . $user_id;
                if (db_update($link, $query) !== TRUE) {
                    $err_msg[] = 'カートテーブルINSERTクエリ失敗';
                } else {
                    $message[] = 'カートに商品を追加しました';
                }
            } else {
                $query = 'INSERT INTO ec_cart_table(user_id, item_id, amount) VALUES (' . $user_id . ',' . $item_id . ',' . $amount . ')';
                if (db_insert($link, $query) !== TRUE) {
                    $err_msg[] = 'カートテーブルINSERTクエリ失敗';
                } else {
                    $message[] = 'カートに商品を追加しました';
                }
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
    $query = 'SELECT ept.id, ept.name, ept.price, ept.img, ept.status, eps.stock
    FROM ec_products_table as ept
    JOIN ec_products_stock as eps
    ON ept.id = eps.item_id
    WHERE ept.status = 1 AND ept.deleted_flg = 0 ';

    if (!($arr_result = db_select($link, $query))) {
        $err_msg[] = 'SELECTクエリ失敗';
    }
    db_close($link);
}
include './include/view/products_list_view.php';

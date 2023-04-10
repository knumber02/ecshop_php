<?php
require_once './include/conf/const.php';
require_once './include/model/function.php';

check_admin_session();
//変数宣言
$product_name = '';
$price = 0;
$stock = 0;
$status = 0;
$product_img = '';
$arr_result = [];
//メッセージ変数
$err_msg = [];
$massage = [];
// データベース接続
$link = db_connect();
if ($link) {
    // 文字コードセット
    mysqli_set_charset($link, 'UTF-8');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql_kind = $_POST['sql_kind'];
        if ($sql_kind === 'insert') {
            //POST受け取り
            $product_name = $_POST['name'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $status = $_POST['status'];
            if (isset($_FILES['product_img'])) {
                $product_img = $_FILES['product_img'];
            }
            //エラー処理
            if (strlen($product_name) === 0) {
                $err_msg[] = '商品名を入力してください';
            }
            if (strlen($price) === 0) {
                $err_msg[] = '値段を入力してください';
            } else if (is_numeric($price) !== TRUE || $price < 0) {
                $err_msg[] = '値段は0以上の数字を入力してください';
            }
            if (strlen($stock) === 0) {
                $err_msg[] = '数量を入力してください';
            } else if (is_numeric($stock) !== TRUE || $stock < 0) {
                $err_msg[] = '数量は0以上の数字を入力してください';
            }
            if ($status !== '1' && $status !== '0') {
                $err_msg[] = 'ステータスは公開または非公開です';
            }
            if (empty($product_img['name'])) {
                $err_msg[] = '商品画像を入力してください';
            } else if ($product_img['error'] !== 0) {
                $err_msg[] = '画像のアップロードに失敗しました';
            } else if (!($product_img['type'] === 'image/jpeg' || $product_img['type'] === 'image/png')) {
                var_dump($product_img['type']);
                die();
                $err_msg[] = '画像の拡張子は「.jpeg」または「.png」のみ可能です';
            }
            // 正常処理
            if (empty($err_msg)) {
                $query_created_date = date('Y-m-d H:i:s');
                //トランザクション開始
                mysqli_autocommit($link, false);
                $query = 'INSERT INTO ec_products_table(name, price, status, created_date) VALUES ("' . $product_name . '", '  . $price . ', ' . $status . ',"' . $query_created_date . '")';
                if (db_insert($link, $query) !== TRUE) {
                    $err_msg[] = '商品情報テーブルINSERTクエリ失敗';
                } else {
                    $message[] = '商品情報テーブル INSERTクエリ成功';
                    $product_id = mysqli_insert_id($link);
                    $query_created_date = date('Y-m-d H:i:s');
                    $query = 'INSERT INTO ec_products_stock(item_id, stock, created_date) VALUES (' . $product_id . ',' . $stock . ', "' . $query_created_date . '")';
                    if (db_insert($link, $query) !== TRUE) {
                        $err_msg[] = '商品在庫テーブルINSERTクエリ失敗';
                    } else {
                        //画像アップロード処理
                        $img_title = hased_img($product_img, $product_id);
                        if (upload_file($product_img, $img_title) === false) {
                            $err_msg[] = '画像のアップロードに失敗しました';
                        } else {
                            $query = 'UPDATE ec_products_table SET img = "' . $img_title . '" WHERE id = ' . $product_id;
                            if (db_update($link, $query)) {
                                $message[] = '商品情報テーブル UPDATEクエリ成功';
                            } else {
                                $err_msg[] = 'UPDATEクエリ失敗';
                            }
                        }
                    }
                }
                //トランザクション処理実行
                if (empty($err_msg)) {
                    mysqli_commit($link);
                    $message[] = 'データベース反映成功';
                } else {
                    mysqli_rollback($link);
                    $message[] = 'データベース反映失敗';
                }
            }
        } else if ($sql_kind === 'update') {
            $changed_stock = $_POST['changed_stock'];
            $product_id = $_POST['product_id'];
            if ($changed_stock < 0) {
                $err_msg[] = '在庫数は0以上の数を入力してください';
            }
            if (empty($err_msg)) {
                $query = 'UPDATE ec_products_stock SET stock = ' . $changed_stock . ' WHERE item_id = ' . $product_id;
                if (db_update($link, $query)) {
                    $message[] = '商品の在庫数変更完了';
                } else {
                    $err_msg[] = 'UPDATEクエリ失敗';
                }
            }
        } else if ($sql_kind === 'change') {
            $changed_status = $_POST['changed_status'];
            $product_id = $_POST['product_id'];
            if ($changed_status !== '1' && $changed_status !== '0') {
                $err_msg[] = 'ステータスは公開または非公開です';
            }
            if (empty($err_msg)) {
                if ($changed_status === '1') {
                    $query = 'UPDATE ec_products_table SET status = 0 WHERE id =' . $product_id . ';';
                } else if ($changed_status === '0') {
                    $query = 'UPDATE ec_products_table SET status = 1 WHERE id =' . $product_id . ';';
                }
                if (db_update($link, $query)) {
                    $message[] = '商品のステータス変更完了';
                } else {
                    $err_msg[] = 'UPDATEクエリ失敗';
                }
            }
        } else if ($sql_kind === "delete") {
            $product_id = $_POST['product_id'];
            //トランザクション開始
            mysqli_autocommit($link, false);
            $query = 'UPDATE ec_products_table SET deleted_flg = 1 WHERE id =' . $product_id . ';';

            if (mysqli_query($link, $query)) {
                $message[] = '商品情報テーブル DELETEクエリ成功';
            } else {
                $err_msg[] = 'DELETEクエリ失敗';
            }

            //トランザクション処理実行
            if (empty($err_msg)) {
                mysqli_commit($link);
                $message[] = 'データベース反映成功';
            } else {
                mysqli_rollback($link);
                $message[] = 'データベース反映失敗';
            }
        }
    }
    //GET処理
    $query = 'SELECT ept.id, ept.name, ept.price, ept.img, ept.status, eps.stock
                FROM ec_products_table as ept
                JOIN ec_products_stock as eps
                ON ept.id = eps.item_id
                WHERE ept.deleted_flg = 0';
    if (!($arr_result = db_select($link, $query))) {
        $err_msg[] = 'SELECTクエリ失敗';
    };
    db_close($link);
} else {
    $err_msg[] = 'データベース接続失敗';
}
include_once './include/view/register_products_view.php';

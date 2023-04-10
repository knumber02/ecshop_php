<?php
//関数定義
/**
 * データベースに接続する
 * @return object $link
 */
function db_connect()
{
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
    return $link;
}
/**
 * データベースを切断する
 * @param object $link
 */
function db_close($link)
{
    mysqli_close($link);
}
/**
 * SELECTクエリを実行する
 * @param object $link
 * @param string $query
 * @return bool $result
 */
function db_select($link, $query)
{
    $arr_result = [];
    if ($result = mysqli_query($link, $query)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $arr_result[] = $row;
        }
        // 結果セットを開放
        mysqli_free_result($result);
        return $arr_result;
    } else {
        return false;
    }
}
/**
 * INSERTクエリを実行する
 * @param object $link
 * @param string $query
 * @return array $arr_result
 */
function db_insert($link, $query)
{
    if (mysqli_query($link, $query)) {
        return true;
    } else {
        return false;
    }
}
/**
 * UPDATEクエリを実行する
 * @param object $link
 * @param string $query
 * @return bool $result
 */
function db_update($link, $query)
{
    if (mysqli_query($link, $query)) {
        return true;
    } else {
        return false;
    }
}
/**
 * 画像のハッシュ化＆ファイルの拡張子をつける
 * @param object $product_img
 * @param int $product_id
 * @return bool $result
 */
function hased_img($product_img, $product_id)
{
    $img_title = $product_img['name'] . date('Y-m-d H:i:s') . $product_id;
    if ($product_img['type'] === 'image/jpeg') {
        $img_title = hash('sha256', $img_title) . '.jpeg';
    } else if ($product_img['type'] === 'image/png') {
        $img_title = hash('sha256', $img_title) . '.png';
    }
    return $img_title;
}
/**
 * 画像のアップロード
 * @param object $product_img
 * @param string $img_title
 * @return bool $result
 */
function upload_file($product_img, $img_title)
{
    $upload = STORE_DIR . basename($img_title);
    if (move_uploaded_file($product_img['tmp_name'], $upload)) {
        return true;
    } else {
        return false;
    }
}
/**
 * ログイン処理
 * @param string $passowrd
 * @param string user_name
 * @param string cookie_check
 * @param object $link
 * @param array $err_msg
 * @return void
 */
function login($password, $user_name, $cookie_check, $link, $err_msg)
{
    //管理者ログイン
    if ($user_name === 'admin' && $password === 'admin') {
        $_SESSION['admin_flg'] = true;
        header('Location:resister_products.php');
        exit();
    }
    $now = time();
    //クッキーを利用するかどうか確認
    if ($cookie_check === 'checked') {
        setcookie('cookie_check', $cookie_check, $now + 60 * 60 * 24 * 365);
        setcookie('user_name', $user_name, $now + 60 * 60 * 24 * 365);
    } else {
        setcookie('cookie_check', '', $now - 3600);
        setcookie('user_name', '', $now - 3600);
    }
    $password_hashed = hash('sha256', $password);
    $query = "SELECT id FROM ec_users_table WHERE user_name = '" . $user_name . "' AND password = '" . $password_hashed . "'";
    $data = db_select($link, $query);

    if (count($data) === 0) {
        return false;
    } else {
        //セッションにユーザーIDを保存
        if (isset($data[0]['id'])) {
            $_SESSION['user_id'] = $data[0]['id'];
        }
        header('Location:products_list.php');
        exit();
    }
}
/**
 * ログアウト処理
 * @return void
 */
function logout()
{
    $session_name = session_name();
    // セッション変数をすべて削除
    $_SESSION = [];
    // ログアウトの処理
    $params = session_get_cookie_params();
    setcookie(
        $session_name,
        '',
        time() - 42000,
        $params['path'],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
    // セッションIDを無効化
    session_destroy();
}
/**
 * ユーザーページセッションチェック処理
 * @return void
 */
function check_user_session()
{
    //ユーザーページ
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        header('Location: login.php');
        exit();
    }
    return $user_id;
}
/**
 * 管理ページセッションチェック処理
 * @return void
 */
function check_admin_session()
{
    //管理ページ
    if (!(isset($_SESSION['admin_flg']))) {
        header('Location: login.php');
        exit();
    }
}

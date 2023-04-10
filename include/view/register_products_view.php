<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodeShop管理ページ</title>
</head>

<body>
    <?php include 'admin_header_view.php'; ?>
    <?php if (!empty($message)) { ?>
        <?php foreach ($message as $m) { ?>
            <p><?php print $m; ?></p>
        <?php } ?>
    <?php } ?>
    <?php if (!empty($err_msg)) { ?>
        <?php foreach ($err_msg as $e) { ?>
            <p><?php print $e; ?></p>
        <?php } ?>
    <?php } ?>
    <section>
        <h2>新規商品登録</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div><label>商品名:<input type="text" name="name"></label></div>
            <div><label>値段:<input type="text" name="price"></label></div>
            <div><label>個数:<input type="text" name="stock"></label></div>
            <div>画像:<input type="file" name="product_img" value="ファイルを選択"></div>
            <div>
                公開ステータス:
                <select name=" status" id="">
                    <option value="1">公開</option>
                    <option value="0">非公開</option>
                </select>
            </div>
            <input type="hidden" name="sql_kind" value="insert">
            <div><input type="submit" value="商品追加"></div>
        </form>
    </section>
    <section>
        <h2>商品一覧</h2>
        <table>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>
            <?php foreach ($arr_result as $value) { ?>
                <tr>
                    <form action="" method="post">
                        <td><img src="<?php echo STORE_DIR . $value['img']; ?>" alt="" width="100px" height="100px" /></td>
                        <td><?php print $value['name']; ?></td>
                        <td><?php print $value['price']; ?></td>
                        <td><input ty pe="text" name="changed_stock" value="<?php print $value['stock'] ?>" ;>在庫数<br><input type="submit" value="変更"></td>
                        <input type="hidden" name="product_id" value="<?php print $value['id'] ?>" />
                        <input type="hidden" name="sql_kind" value="update">
                    </form>
                    <form action="" method="post">
                        <td><input type="submit" name="changed_status" value="<?php if ($value['status'] === '1') {
                                                                                    print '公開→非公開';
                                                                                } else {
                                                                                    print '非公開→公開';
                                                                                } ?>">
                        </td>
                        <input type="hidden" name="changed_status" value="<?php print $value['status']; ?>" />
                        <input type="hidden" name="product_id" value="<?php print $value['id'] ?>" />
                        <input type="hidden" name="sql_kind" value="change">
                    </form>
                    <form action="" method="post">
                        <td>
                            <input type="submit" name="delete_button" value="削除する"></input>
                        </td>
                        <input type="hidden" name="product_id" value="<?php print $value['id'] ?>" />
                        <input type="hidden" name="sql_kind" value="delete">
                    </form>
                </tr>
            <?php } ?>
        </table>
    </section>
</body>

</html>

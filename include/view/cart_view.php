<?php include 'head_view.php'; ?>
<?php include 'user_header_view.php'; ?>
<main>
    <div class="main-container">
        <h2 class="page-title">カート内一覧</h2>
        <section class="message-container">
            <?php foreach ($err_msg as $e) { ?>
                <p>※<?php print $e ?></p>
            <?php } ?>
            <?php if (count($message) > 0) {
                foreach ($message as $m) {  ?>
                    <p><?php print $m ?></p>
            <?php }
            } ?>
        </section>
        <section class="cart-container">
            <table class="cart-table">
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>値段</th>
                    <th>個数</th>
                </tr>
                <?php if ($arr_result && count($arr_result) > 0) {
                    foreach ($arr_result as $value) { ?>
                        <tr>
                            <td><img src="<?php echo STORE_DIR . $value['img']; ?>" alt="" width="150px" height="150px"></td>
                            <td>
                                <form action="cart.php" method="post">
                                    <?php print $value['name'] ?>
                                    <input type="hidden" name="sql_kind" value="delete"></input>
                                    <input type="hidden" name="item_id" value="<?php print $value['id'] ?>"></input>
                                    <input type="submit" name="delete_amount" value="削除"></input>
                                </form>
                            </td>
                            <td>￥<?php print $value['price'] ?></td>
                            <td>
                                <form action="cart.php" method="post">
                                    <input type="text" name="amount" value="<?php print $value['amount'] ?>" />
                                    <input type="hidden" name="sql_kind" value="change"></input>
                                    <input type="hidden" name="item_id" value="<?php print $value['id'] ?>"></input>
                                    <input type="submit" name="change_amount" value="変更"></input>
                                </form>
                            </td>
                        </tr>
                        </form>
                <?php }
                } ?>
            </table>
            <div>合計：￥<?php print $total; ?></div>
            <div>
                <form action="finish.php" method="post" class="d-grid gap-2 col-6 mx-auto">
                    <button class=" btn btn-primary btn-lg" id="buy-btn" type=" submit" value="">購入する</button>
                </form>
            </div>
        </section>
    </div>
</main>

<?php include 'head_view.php'; ?>
<?php include 'user_header_view.php'; ?>
<main>
    <div class="main-container">
        <h2 class="page-title">購入商品一覧</h2>
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
                                <?php print $value['name'] ?>
                            </td>
                            <td>￥<?php print $value['price'] ?></td>
                            <td>
                                <?php print $value['amount'] ?>個
                            </td>
                        </tr>
                <?php }
                } ?>
            </table>
            <div>合計：￥<?php print $total; ?></div>
        </section>
</main>

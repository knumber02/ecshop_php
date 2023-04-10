<?php include 'head_view.php'; ?>
<?php include 'user_header_view.php'; ?>
<main>
    <div class="main-container">
        <h1 class="page-title">商品一覧</h1>
        <section class="message-container">
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
            <div>
        </section>
        <section>
            <div class="products-table">
                <?php foreach ($arr_result as $key => $value) { ?>
                    <div class="item">
                        <form action="./products_list.php" method="post">
                            <img src="<?php echo STORE_DIR . $value['img']; ?>" alt="" width="300px" height="300px">
                            <div class="item-info">
                                <span>商品名:<?php print $value['name'] ?></span>
                                <span>値段: <?php print $value['price'] ?>円</span>
                            </div>
                            <?php if ($value['stock'] > 0) { ?>
                                <input type="submit" value="カートに入れる"></input>
                            <?php } else { ?>
                                <p class="stock_message">売り切れ</p>
                            <?php } ?>
                            <input type="hidden" name="item_id" value="<?php echo $value['id']; ?>">
                            <input type="hidden" name="sql_kind" value="insert_cart" /></input>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>
    </div>
</main>

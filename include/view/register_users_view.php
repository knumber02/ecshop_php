<?php include 'head_view.php'; ?>
<?php include 'user_header_view.php'; ?>
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
<div class="login-main-container">
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            <label>ユーザー名:<input type="text" placeholder="ユーザー名" name="user_name"></label>
        </div>
        <div>
            <label>パスワード:<input type="text" placeholder="パスワード" name="password"></label>
        </div>
        <div>
            <input type="submit" value="ユーザーを新規登録する" name="register_button" />
        </div>
        <a href="/php25/login.php">ログインページに移動する</a>
    </form>
</div>
</body>

</html>

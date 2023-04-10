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
    <form action="login.php" method="POST" enctype="multipart/form-data">
        <div>
            <label for="user_name">ユーザー名:<input type="text" class="block" id="user_name" name="user_name" value="<?php print $user_name; ?>"></label>
        </div>
        <div>
            <label for="passwd">パスワード:<input type="password" class="block" id="passwd" name="password"></label>
        </div>
        <div>
            <span class="block small"><input type="checkbox" name="cookie_check" value="checked" checked="checked">次回からユーザ名の入力を省略</span>
            <input type="submit" value="ログイン" name="register_button" />
        </div>
    </form>
    <a href="/php25/register_users.php">ユーザーの新規登録</a>
</div>
</body>

</html>

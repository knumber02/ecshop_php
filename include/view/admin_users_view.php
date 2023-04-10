<body>
    <?php include 'admin_header_view.php'; ?>
    <section>
        <table>
            <h2>ユーザー情報一覧</h2>
            <tr>
                <th>ユーザーネーム</th>
                <th>登録日</th>
            </tr>
            <?php foreach ($arr_result as $value) { ?>
                <tr>
                    <td><?php print $value['user_name'] ?></td>
                    <td><?php print $value['created_date'] ?></td>
                </tr>
            <?php } ?>
        </table>
    </section>
</body>

</html>

<?php
require_once( dirname( __DIR__, 3 ) . '/wp-blog-header.php' );
session_start();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<?php get_header(); ?>
<main>
    <?php get_template_part('part','member'); ?>


    <div class="register">
        <?php echo show_flash_message(); ?>
        <form method="POST" action="./api/cost/create.php">
            <input type="hidden" name="is_post" value="1"><!-- プログラム制御用 -->
            <div class="subject">
                <label for="subject">件名</label><input id="subject" type="text" name="subject" placeholder="イオン" value="">
            </div>
            <div class="price">
                <label for="price">金額</label><input id="price" type="number" name="price" placeholder="100" value="">
            </div>
            <div class="checkbox">
                <label><input class="radio" type="checkbox" name="kind" value="食費" checked="checked">食費</label>
                <label><input class="radio" type="checkbox" name="kind" value="交際費">交際費</label>
                <label><input class="radio" type="checkbox" name="kind" value="雑費">雑費</label>
                <label><input class="radio" type="checkbox" name="utility" value="水道代">水道代</label>
                <label><input class="radio" type="checkbox" name="utility" value="ガス代">ガス代</label>
                <label><input class="radio" type="checkbox" name="utility" value="電気代">電気代</label>
                <label><input class="radio" type="checkbox" name="utility" value="通信費">通信費</label>
            </div>
            <!-- <div class="add">
                <label for="add">分類追加:</label><input id="add" type="text" name="new" placeholder="例) 習い事" value="">
            </div> -->
            <div class="member">
                <?php echo show_member_list();?>
            </div>
            <div class="register-btn">
                <button class="create" type="submit">登録</button>
            </div>
        </form>
    </div>

</main>

<?php get_footer(); ?>

<script>
$(".radio").on("click", function() {
    $('.radio').prop('checked', false); // チェックを外す
    $(this).prop('checked', true); // チェックつける
});
</script>
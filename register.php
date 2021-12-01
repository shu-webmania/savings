<?php
require_once( dirname( __DIR__, 3 ) . '/wp-blog-header.php' );
$user = wp_get_current_user(); //現在のログインユーザー取得
$userName = $user->display_name; //ユーザー名
$member_terms = get_terms( 'member', array( 'hide_empty'=>false)); //メンバーターム取得
$current_month = date('n'); //今月を取得
$last_month = date('n', strtotime('-1 month')); //先月を取得

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<?php get_header(); ?>
<main>
    <div class="member">
        <span class="member_tit">共有メンバー:</span>
        <?php foreach( $member_terms as $term): 
      $term_name = $term->name; //ターム名
      $term_slug = $term->slug; //タームスラッグ
      $term_core = explode( "-" , $term_slug ); //タームスラッグ分解
    ?>
        <?php 
    if( $userName == $term_core[1] ) : //ログイン中のユーザーとタームを照合
    ?>
        <span class="member__name">
            <?php echo $term_name ?>,
        </span>
        <?php endif; ?>
        <?php endforeach; ?>
        <a href="#" class="member__addBtn">+</a>
    </div>

    <div class="register">
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
            <div class="add">
                <label for="add">分類追加:</label><input id="add" type="text" name="new" placeholder="例) 習い事" value="">
            </div>
            <div class="member">
                <?php foreach( $member_terms as $term): 
                    $term_name = $term->name;
                ?>

                <label><input type="radio" name="member" value="<?php echo $term_name ?>" checked="checked"><?php echo $term_name ?></label>
                <?php endforeach; ?>
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
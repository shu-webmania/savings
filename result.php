<?php
require_once( dirname( __DIR__, 3 ) . '/wp-blog-header.php' );
$total = 0;
$user = wp_get_current_user(); //現在のログインユーザー取得
$userName = $user->display_name; //ユーザー名
$terms = get_terms( 'member', array( 'hide_empty'=>false)); //メンバーターム取得
$current_month = date('n'); //今月を取得
$last_month = date('n', strtotime('-1 month')); //先月を取得
$userID = $user->ID; // ユーザーID
$args = array(
  'post_type' => 'cost',
  'nopaging'  => true, 
  'post_per_page' => -1,
  'author'	   => $userID,
);
$cost_query = get_posts($args); //"cost"投稿を取得
?>
<?php get_header(); ?>

<main>
    <div class="member">
        <span class="member_tit">共有メンバー:</span>
        <?php foreach( $terms as $term): 
        $term_name = $term -> name; //ターム名
        $term_slug = $term -> slug; //タームスラッグ
        $term_core = explode( "-" , $term_slug ); //タームスラッグ分解
      ?>
        <?php 
      if( $userName == $term_core[0] ) : //ログイン中のユーザーとタームを照合
      ?>
        <span class="member__name">
            <?php echo $term_name ?>,
        </span>
        <?php endif; ?>
        <?php endforeach; ?>
        <a href="#" class="member__addBtn">+</a>
    </div>

    <div class="result">
        <?php
    if(!empty($cost_query)): foreach($cost_query as $post): setup_postdata($post);
    $price = get_post_meta( get_the_ID(), 'price', true ); //金額を取得
    $total += intval($price); //totalへループ内の金額を代入
    ?>

        <form method="POST" action="./api/cost/delete.php">
            <input type="hidden" name="is_post" value="1"><!-- プログラム制御用 -->
            <div>
                <span><?php the_title(); ?></span>¥<span><?php echo number_format($price); ?></span>日時:<?php echo get_the_date( $format, $post ); ?><span></span>
                <label for="delete">削除<input id="delete" type="checkbox" name="delete[]" value=<?php the_ID(); ?>></label>
            </div>
            <?php endforeach; elseif(empty($cost_query)): ?>
            <p>まだ登録はありません</p>
            <?php endif; wp_reset_postdata(); ?>
            <div>合計金額:¥<?php print number_format($total); ?></div>
            <button class="delete" type="submit">削除実行</button>
        </form>
    </div>
</main>

<?php get_footer(); ?>
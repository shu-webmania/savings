<?php
require_once( dirname( __DIR__, 3 ) . '/wp-blog-header.php' );
$user = wp_get_current_user(); //現在のログインユーザー取得
$userName = $user->display_name; //ユーザー名
?>

<form method="POST" action="./api/member/create.php">
    <input type="hidden" name="is_post" value="1"><!-- プログラム制御用 -->
    <label for="member">メンバー:</label><input id="member" type="text" name="member" value="">
    <div>
        <button class="create" type="submit">登録</button>
    </div>
</form>

<?php 
  $terms = get_terms( 'member', array( 'hide_empty' => false)); //メンバーターム取得
?>

<div>共有メンバー</div>

<?php foreach( $terms as $term): 
	$term_name = $term -> name; //ターム名
	$term_slug = $term -> slug; //タームスラッグ
	$term_core = explode( "-" , $term_slug ); //タームスラッグ分解
?>
<?php 
if( $userName == $term_core[0] ) : //ログイン中のユーザーとタームを照合
?>
<div>・<?php echo $term_name ?></div>
<?php endif; ?>
<?php endforeach; ?>
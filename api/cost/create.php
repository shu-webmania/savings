<?php
require_once( dirname( __DIR__, 5 ) . '/wp-blog-header.php' ); //WP関数使用させるため

if (isset($_POST['is_post'])) { //methodがpostの場合発動
    $arg = array(
   		'post_status' => 'publish',
		'post_title' => $_POST['subject'],
		'post_content' => '',
		'post_type' => 'cost',
		'post_author' => $user_ID,
	);
	$post_id = wp_insert_post($arg); //カスタム投稿登録
	
	add_post_meta($post_id, "price", $_POST['price']); //金額登録
	
	$new = $_POST['new'];
	if (empty($new)){
		wp_set_object_terms($post_id, $_POST['kind'], "kind"); //デフォルト出費分類登録
		wp_set_object_terms($post_id, $_POST['utility'], "utility"); //光熱費出費分類登録
	}else{
		wp_set_object_terms($post_id, $_POST['new'], "kind"); //新規出費分類登録
	}

	wp_set_object_terms($post_id, $_POST['member'], "member"); //メンバー情報登録
}


wp_redirect( get_stylesheet_directory_uri() . "/register.php" ); //result.phpへリダイレクト
exit;
?>
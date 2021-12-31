<?php
require_once( dirname( __DIR__, 5 ) . '/wp-blog-header.php' ); //WP関数使用させるため
$get_member = $_GET['member'];
$user = wp_get_current_user(); //現在のログイン中ユーザー情報取得
$userName = $user->display_name; //ユーザー名 

if (isset($_POST['is_post'])) { //methodがpostの場合発動
    foreach($_POST['delete'] as $ID){
        wp_delete_post($ID, true); //削除
    }
}

wp_redirect( home_url().'/member/'. $get_member.'-'.$userName ); //リダイレクト
exit();
?>
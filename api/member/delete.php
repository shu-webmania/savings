<?php
require_once( dirname( __DIR__, 5 ) . '/wp-blog-header.php' ); //WP関数使用させるため

if (isset($_POST['is_post'])) { //methodがpostの場合発動
    foreach($_POST['delete'] as $ID){
        wp_delete_post($ID, true); //カスタム投稿登録
    }
}

// debug($_POST['delete']);

// function debug($val) //デバック関数
// {
//     print '<pre>';
//     print_r($val);
//     print '</pre>';
// }

wp_redirect( get_stylesheet_directory_uri() . "/result.php" ); //result.phpへリダイレクト
exit;
?>
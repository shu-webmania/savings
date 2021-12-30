<?php
$user = wp_get_current_user(); //現在のログインユーザー取得
$userName = $user->display_name; //ユーザー名
?>

<?php get_header(); ?>

<main>
    <?php get_template_part('part','member'); ?>
    <div class="container">
        <?php echo show_member_cost_post(); ?>
        <a href="<?php echo get_template_directory_uri().'/pay.php'; ?>" class="cost__btn">先月の精算する</a>
    </div>
</main>

<?php get_footer(); ?>
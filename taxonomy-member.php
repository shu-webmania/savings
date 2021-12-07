<?php
$user = wp_get_current_user(); //現在のログインユーザー取得
$userName = $user->display_name; //ユーザー名
?>

<?php get_header(); ?>

<main>
    <?php get_template_part('part','member'); ?>

    <div class="container">
        <div class="member-cost">
            <?php echo show_single_member_cost_post(); ?>
        </div>
        <a href="<?php echo get_template_directory_uri().'/register.php' ?>" class="cost__btn">費用追加</a>
    </div>
</main>

<?php get_footer(); ?>
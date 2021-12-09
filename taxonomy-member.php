<?php
$user = wp_get_current_user(); //現在のログインユーザー取得
$userName = $user->display_name; //ユーザー名
$member_terms = get_the_terms(get_the_ID(),'member');
?>

<?php get_header(); ?>

<main>
    <?php get_template_part('part','member'); ?>

    <div class="container">
        <div class="member-cost">
            <?php echo show_single_member_cost_post(); ?>
        </div>
        <a href="<?php echo get_template_directory_uri().'/register.php?member='.$member_terms[0]->name;?>" class="cost__btn">費用追加</a><!-- getパラメーターの処理やってます -->
    </div>
</main>

<?php get_footer(); ?>
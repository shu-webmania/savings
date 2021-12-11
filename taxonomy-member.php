<?php
$member_terms = get_the_terms(get_the_ID(),'member');
?>

<?php get_header(); ?>

<main>
    <?php get_template_part('part','member'); ?>

    <div class="container">
        <form method="POST" action="<?php echo get_stylesheet_directory_uri() . "/api/cost/delete.php?member=".$member_terms[0]->name ;?>">
            <input type="hidden" name="is_post" value="1"><!-- プログラム制御用 -->
            <div class="member-cost taxonomy">
                <?php echo show_single_member_cost_post(); ?>
            </div>
            <button class="delete" type="submit">削除実行</button>
            <a href="<?php echo get_template_directory_uri().'/register.php?member='.$member_terms[0]->name;?>" class="cost__btn">費用追加</a><!-- getパラメーターの処理やってます -->
        </form>
    </div>
</main>

<script>
function test1() {
    jQuery(function($) {
        $(".member-cost__delete").toggleClass("active");
    });
}
</script>

<?php get_footer(); ?>
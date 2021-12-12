<?php get_header(); ?>

<main>
    <?php get_template_part('part','member'); ?>

    <div class="container">
        <form method="POST" action="<?php echo get_stylesheet_directory_uri() . "/api/cost/delete.php?member=".single_term_title('',false) ;?>">
            <input type="hidden" name="is_post" value="1"><!-- プログラム制御用 -->
            <div class="member-cost taxonomy">
                <?php echo show_single_member_cost_post(); ?>
            </div>
            <button class="delete-btn" type="submit">削除実行</button>
            <a href="<?php echo get_template_directory_uri().'/register.php?member='.single_term_title('',false) ;?>" class="cost__btn">費用追加</a><!-- getパラメーターの処理やってます -->
        </form>
    </div>
</main>

<script>
function onOff(obj) {
    jQuery(function($) {
        $(obj).parent().parent().toggleClass("active");
        if ($('.member-cost__delete').hasClass("active")) {
            $('.delete-btn').addClass("visible");
        } else {
            $('.delete-btn').removeClass("visible");
        }
    });
}
</script>

<?php get_footer(); ?>
<?php 
require_once( dirname( __DIR__, 3 ) . '/wp-blog-header.php' );
get_header(); 
?>

<main>
    <?php get_template_part('part','member'); ?>

    <div class="container">
        <div class="member-cost__name">支払い</div>
        <div class="member-cost__total">
            <?php echo show_member_cost_pay_post(); ?>
        </div>
        <a href="<?php echo home_url("/"); ?>" class="cost__btn">ホームへ戻る</a>
    </div>
</main>

<?php get_footer(); ?>
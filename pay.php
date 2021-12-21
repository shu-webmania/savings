<?php 
require_once( dirname( __DIR__, 3 ) . '/wp-blog-header.php' );
get_header(); 
?>

<main>
    <?php get_template_part('part','member'); ?>

    <div class="container">
        <?php echo show_member_cost_pay_post(); ?>
    </div>
</main>

<?php get_footer(); ?>
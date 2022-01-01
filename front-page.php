<?php get_header(); ?>
<main>
    <?php get_template_part('part','member'); ?>

    <div class="container">
        <div class="cost">
            <div class="cost__total">生活費</div>
            <?php echo show_kind_post(); ?>
        </div>
        <div class="utility">
            <div class="utility__total">光熱費</div>
            <?php echo show_utility_post(); ?>
        </div>
        <a href="<?php echo home_url("cost/"); ?>" class="cost__btn">費用確認</a>
    </div>
</main>

<?php get_footer(); ?>
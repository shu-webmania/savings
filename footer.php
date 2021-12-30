<?php
$stylesheet_uri = get_stylesheet_directory_uri();
?>

<footer class="footer">
    <?php if(is_page_template('register.php')) :?>
    <a href="<?php echo get_stylesheet_directory_uri() . "/register.php" ?>" class="costBtn">費用追加</a>
    <?php endif; ?>
</footer>

<?php wp_footer(); ?>
<script>
//画面の残りはfooterの領域展開
jQuery(function($) {
    var winH = $(window).height();
    tillfooter = $('.footer').offset();
    footerH = winH - tillfooter.top;

    $('.footer').outerHeight(footerH);
});
</script>
</body>

</html>
<?php get_header(); ?>
<main>
    <div class="member">
        <span class="member_tit">共有メンバー:</span>
        <span class="member__name">
            <?php 
            $user = wp_get_current_user(); //現在のログイン中ユーザー情報取得
            $userName = $user->display_name; //ユーザー名    
            $member_terms = get_terms( 'member', array( 'hide_empty'=>false)); //メンバーターム取得
            foreach( $member_terms as $term){
                $term_core = split_terms($term);
                if($userName == $term_core[1]){
                    echo show_member_html($term->name);
                }
            } 

            function show_member_term(){
                
            }
            ?>
        </span>
        <a href="<?php echo get_theme_file_uri('member.php'); ?>" class="member__addBtn">+</a>


    </div>

    <div class="container">
        <div class="cost">
            <div class="cost__total">出費合計</div>
            <?php echo show_kind_post(); ?>
        </div>
        <div class="utility">
            <div class="utility__total">固定費合計</div>
            <?php echo show_utility_post(); ?>
        </div>
        <a href="<?php echo home_url("cost/"); ?>" class="cost__btn">費用確認</a>
    </div>
</main>

<?php get_footer(); ?>
<?php
$user = wp_get_current_user(); //現在のログインユーザー取得
$userName = $user->display_name; //ユーザー名
?>

<?php get_header(); ?>

<?php 
    $member_terms = get_terms( 'member', array( 'hide_empty'=>false)); //メンバーターム取得
    if(!empty($member_terms)){
        foreach($member_terms as $term){
            $post_by_term = get_post_by_member_term($term);
            $total_current = 0;
            while($post_by_term->have_posts()){
                $post_by_term->the_post();
                $price_current = get_post_current_cost();//今月出費
                $total_current += intval($price_current); //今月出費計算
            }
            echo $term->name;
            echo $total_current;
        }
    }else{
    }
    ?>
<a href="<?php echo get_term_link($term->term_id); ?>" class="userName"><?php echo esc_html( $term->name ); ?></a>





<?php get_footer(); ?>
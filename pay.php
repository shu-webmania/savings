<?php 
require_once( dirname( __DIR__, 3 ) . '/wp-blog-header.php' );
get_header(); 
?>

<main>
    <?php get_template_part('part','member'); ?>

    <div class="container">
        <?php 
        function show_member_cost_pay_post(){
            $member_terms = get_terms( 'member', array( 'hide_empty'=>false)); //メンバーターム取得
            $html = '';
                foreach($member_terms as $term){
                    $post_by_term = get_post_by_member_term($term);
                    $total_current = 0;
                    $term_link = get_term_link($term->slug, 'member');
                    while($post_by_term->have_posts()){
                        $post_by_term->the_post();
                        $price_current = get_post_current_cost();//今月出費
                        $total_current += intval($price_current); //今月出費計算
                    }
                    $html .= '<a href="'. $term_link .'" class="member-cost">';
                    $html .= '<div class="member-cost__name">'. $term->name .'</div>';
                    $html .= '<div class="member-cost__total">';
                    $html .= '<span class="sum">合計</span><span class="cost-current">¥'. number_format($total_current) .'</span>';
                    $html .= '</div>';
                    $html .= '</a>';
                }
                return $html ;
        }
        ?>
    </div>
</main>

<?php get_footer(); ?>
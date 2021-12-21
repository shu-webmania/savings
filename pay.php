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
            $sum = 0;
            $members =[];
            $i = 0;
            foreach($member_terms as $term){
                $post_by_term = get_post_by_member_term($term);
                $total_last = 0;
                $term_link = get_term_link($term->slug, 'member');
                while($post_by_term->have_posts()){
                    $post_by_term->the_post();
                    $price_last = get_post_last_cost();
                    $total_last += intval($price_last); 
                    $sum += intval($price_last); 
                }
                $members[] = [
                    "name" => $term->name,
                    "price" => $total_last
                ]; 
                if($i == 1){
                    $ave = intval(round($sum/2));  
                    $members[0]["pay"] = $ave - $members[0]["price"];
                    $members[1]["pay"] = $ave - $members[1]["price"];
                    debug($members);
                    if($members[0]["price"] < $members[1]["price"]){
                        $html ='';
                        $html = $members[0]["name"].'から'.$members[1]["name"].'へ'. number_format($members[0]["pay"]) .'円支払う'; 
                    }elseif($members[1]["price"] < $members[0]["price"]){
                        $html ='';
                        $html = $members[1]["name"].'から'.$members[0]["name"].'へ'. number_format($members[1]["pay"]) .'円支払う'; 
                    }else{
                        $html ='';
                        $html = '支払い必要なし';
                    }
                }
                $i++;
            }
            return $html ;
        }
        echo show_member_cost_pay_post();
        ?>
    </div>
</main>

<?php get_footer(); ?>
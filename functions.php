<?php 
// 自動保存無効
function disable_quickpress() {
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}
add_action('wp_dashboard_setup', 'disable_quickpress');

// クイックドラフト無効
function disable_autosave() {
    wp_deregister_script('autosave');
  }
  add_action( 'wp_print_scripts', 'disable_autosave' );

  /* -------------------------------------------
   cssの読み込みに関する記述
-------------------------------------------*/
function register_style()
{
	$stylesheet_uri = get_stylesheet_directory_uri();
	wp_register_style('style', $stylesheet_uri  . '/css/style.css');
}
function my_enqueue_style()
{
	// 共通
	register_style();
	wp_enqueue_style('style');
}
add_action('wp_print_styles', 'my_enqueue_style');

/* -------------------------------------------
   jsの読み込みに関する記述
-------------------------------------------*/
function register_script()
{
	$stylesheet_uri = get_stylesheet_directory_uri();
	wp_register_script('script-js', $stylesheet_uri . '/js/script.js');
}
function my_enqueue_script()
{
	// 共通
	if (!is_admin()) {
		wp_deregister_script('jquery'); // WordPress本体のjquery.jsを読み込まない
		wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'); // jQueryの読み込み
	} else {
		wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'); // 管理画面のみ古いバージョンのjquery読み込み
	}
	register_script();
	wp_enqueue_script('script-js');
}
add_action('wp_enqueue_scripts', 'my_enqueue_script');

/* -------------------------------------------
   HTML5でscriptとstyleはtypeが省略可能になったためそれへの対応
-------------------------------------------*/
function remove_style_type($tag)
{
	$tag = str_replace(" type='text/css'", '', $tag);
	$tag = str_replace(' type="text/css"', '', $tag);
	return $tag;
}
add_filter('style_loader_tag', 'remove_style_type', 999);

function remove_script_type($tag)
{
	$tag = str_replace(" type='text/javascript'", '', $tag);
	$tag = str_replace(' type="text/javascript"', '', $tag);
	return $tag;
}
add_filter('script_loader_tag', 'remove_script_type', 999);

/* -------------------------------------------
   不要なwp_head情報を削除
-------------------------------------------*/
remove_action('wp_head', 'wp_generator'); //generator削除
remove_action('wp_head', 'rsd_link'); //外部サービス使用しないので削除
remove_action('wp_head', 'wlwmanifest_link'); //Microsoftが提供するブログエディター「Windows Live Writer削除
remove_action('wp_head', 'print_emoji_detection_script', 7); //emoji削除
remove_action('wp_print_styles', 'print_emoji_styles', 10); //emojiスタイルシート削除


/* -------------------------------------------
   全てのバージョン情報 ?ver=を削除
-------------------------------------------*/
function remove_cssjs_ver($src)
{
	if (strpos($src, 'ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}
add_filter('style_loader_src', 'remove_cssjs_ver', 9999);


/**
 * debug
 * デバック
 * @param string
 * $val:表示させたいもの
 */
function debug($val) 
{
    print '<pre>';
    print_r($val);
    print '</pre>';
}

/**
 * get_post_by_kind_term
 * 出費タームごとの投稿取得
 * @param object
 * $term:ターム情報
 * @return object WP_Query
 */
function get_post_by_kind_term($term){
	$kind_args = '';
	$kind_args = array(
		'post_type' => 'cost',
		'taxonomy' => 'kind',
		'term' => $term->slug,
		'nopaging'  => true, 
		'posts_per_page' => -1,
		'no_found_rows' => true,
	);
	$post_by_term = new WP_Query($kind_args);  
	return $post_by_term;
}

/**
 * get_post_by_utility_term
 * 固定費タームごとの投稿取得
 * @param object
 * $term:ターム情報
 * @return object WP_Query
 */
function get_post_by_utility_term($term){
	$utility_args = '';
	$utility_args = array(
		'post_type' => 'cost',
		'taxonomy' => 'utility',
		'term' => $term->slug,
		'nopaging'  => true, 
		'posts_per_page' => -1,
		'no_found_rows' => true,
	);
	$post_by_term = new WP_Query($utility_args);  
	return $post_by_term;
}

/**
 * get_post_current_cost
 * 今月費用取得
 * @return string
 */
function get_post_current_cost(){
	$current_month = date('m',strtotime('+9hour')); //今月を取得
	$post_month = get_the_date('m'); 
	
	if( $post_month == $current_month ){
		$price_current = get_post_meta( get_the_ID(), 'price', true ); //今月金額を取得
	} 
	return $price_current;
}

/**
 * get_post_last_cost
 * 先月費用取得
 * @return string
 */
function get_post_last_cost(){
	$last_month = date('m', strtotime(date('Y-m-1').' -1 month')); //先月を取得
	$post_month = get_the_date('m'); 
	
	if( $post_month == $last_month ){
		$price_last = get_post_meta( get_the_ID(), 'price', true ); //先月金額を取得
	} 
	return $price_last;
}

/**
 * get_cost_html
 * トップページ出費合計表示html
 * @param string
 * $termName:名前
 * $termLast:先月金額
 * $termCurrent:今月金額
 * @return string
 */
function get_cost_html($termName,$totalLast,$totalCurrent){
	$html = '';
	$html .= '<div class="cost__cards">';
	$html .= '<span class="cost__kindName">' . $termName . '</span>';
	$html .= '<span class="cost__last">¥' . number_format($totalLast) .'</span>';
	$html .= '<span class="cost__current">¥' . number_format($totalCurrent) . '</span>';
	$html .= '</div>';
	return $html;
}

/**
 * get_post_by_member_term
 * メンバータームごとの投稿取得
 * @param object
 * $term:ターム情報
 * @return object WP_Query
 */
function get_post_by_member_term($term){
	$member_args = '';
	$member_args = array(
		'post_type' => 'cost',
		'taxonomy' => 'member',
		'term' => $term->slug,
		'nopaging'  => true, 
		'posts_per_page' => -1,
		'no_found_rows' => true,
	);
	$post_by_term = new WP_Query($member_args);  
	return $post_by_term;
}

/**
 * split_terms
 * ターム分解
 * @param object
 * $term:ターム情報
 * @return string
 */
function split_terms($term){
	$term_slug = $term->slug; 
	$term_core = explode( "-" , $term_slug ); //タームスラッグ分解
	return $term_core;
}

/**
 * show_member_term
 * 登録済みメンバー表示
 * @return string
 */
function show_member_term(){
	$html = '';
	$user = wp_get_current_user(); //現在のログイン中ユーザー情報取得
	$userName = $user->display_name; //ユーザー名    
	$member_terms = get_terms( 'member', array( 'hide_empty'=>false)); //メンバーターム取得
	foreach( $member_terms as $term){
		$term_core = split_terms($term);
		if($userName == $term_core[1]){
			$html .= get_member_html($term->name);
		}
	} 
	return $html;
}


/**
 * get_member_html
 * メンバーレコード生成
 * @param string
 * $name:メンバー名
 * @return $html
 */
function get_member_html($name){
	$html = '';
	$html .= '<span class="member__name">' . $name . ',' . '</span>';
	return $html;
}

/**
 * show_kind_post
 * トップページ出費用レコード生成
 * @return $html
 */
function show_kind_post(){
	$kind_terms = get_terms('kind');
	$html = '';
	if(!empty($kind_terms)){
		foreach($kind_terms as $term){
			$post_by_term = get_post_by_kind_term($term);
			$total_current = 0;
			$total_last = 0;
			
			while($post_by_term->have_posts()){
				$post_by_term->the_post();
				
				$price_current = get_post_current_cost();//今月出費
				$price_last = get_post_last_cost();//先月出費

				$total_current += intval($price_current); //今月出費計算
				$total_last += intval($price_last); //先月出費計算
			}
			wp_reset_postdata();
			$html .= get_cost_html($term->name,$total_last,$total_current);
		}
		return $html;
	}else{
		$html .= get_cost_html('登録なし','0','0');
		return $html;
	}
}

/**
 * show_utility_post
 * トップページ固定費用レコード生成
 * @return $html
 */

function show_utility_post(){
	$utility_terms = get_terms('utility');
	$html = '';
	if(!empty($utility_terms)){
		foreach($utility_terms as $term){
			$post_by_term = get_post_by_utility_term($term);
			$total_current = 0;
			$total_last = 0;
			
			while($post_by_term->have_posts()){
				$post_by_term->the_post();
				
				$price_current = get_post_current_cost();//今月出費
				$price_last = get_post_last_cost();//先月出費

				$total_current += intval($price_current); //今月出費計算
				$total_last += intval($price_last); //先月出費計算
			}
			wp_reset_postdata();
			$html .= get_cost_html($term->name,$total_last,$total_current);
		}
		return $html;
	}else{
		$html .= get_cost_html('登録なし','0','0');
		return $html;
	}
}

/**
 * show_member_cost_post
 * archive-cost用レコード生成
 * @return $html
 */

function show_member_cost_post(){
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

/**
 * get_post_by_single_member_term
 * メンバー個人の投稿取得
 * @param object
 * $term:ターム情報
 * @return object WP_Query
 */
function get_post_by_single_member_term($term){
	$tag_archive = get_query_var('member');
	$member_args = '';
	$get_year = date('y');
	$get_month = date('m',strtotime('+9hour'));
	$member_args = array(
		'post_type' => 'cost',
		'term' => $term->slug,
		'tax_query' => array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'member',
				'field' => 'slug',
				'terms' => $tag_archive,
			),
		),
		'date_query' => array(
			array(
				'after' => array(
					'year' => '20'.$get_year,
					'month' => $get_month,
				),
				'before' => array(
					'year' => '20'.$get_year,
					'month' => $get_month,
				),
				'inclusive' => true,
			),
		),
		'orderby' => 'post_date',
		'nopaging'  => true, 
		'posts_per_page' => -1,
		'no_found_rows' => true,
	);
	$post_by_term = new WP_Query($member_args);
	return $post_by_term;
}

/**
 * show_sigle_member_cost_post
 * taxonomy-member用レコード生成
 * @return $html
 */

function show_single_member_cost_post(){
	$html = '';
	$member_terms = get_the_terms(get_the_ID(),'member');
	foreach((array)$member_terms as $term){
	}
	$post_by_term = get_post_by_single_member_term($term);
	$total_current = 0;
	$i = 0;
	
	$html .= '<div class="member-cost__name">'. $term->name .'</div>';
	if($post_by_term->have_posts()){
		while($post_by_term->have_posts()){
			$post_by_term->the_post();
			$price_current = get_post_current_cost();//今月出費
			$total_current += intval($price_current); //今月出費計算
			$i++;
			
			$html .= '<div class="member-cost__totalbox">';
			$html .= '<div class="member-cost__total delete">';
			$html .= '<div class="member-cost__total__shop">';
			$html .= '<span class="name">'. get_the_title() .'</span><date class="date">'. get_the_date() .'</date>';
			$html .= '</div>';
			$html .= '<div class="member-cost__total__price">¥'. number_format($price_current) .'</div>';
			$html .= '</div>';
			$html .= '<div class="member-cost__delete">';
			$html .= '<label for="delete-'. $i .'">';
			$html .= '削除<input id="delete-'. $i .'" type="checkbox" name="delete[]" onclick="onOff(this)" value=' .get_the_ID(). '>';
			$html .= '</label>';
			$html .= '</div>';
			$html .= '</div>';
		}
	}else{
		$html .= '<div class="member-cost__totalbox">';
		$html .= '<div class="member-cost__total">';
		$html .= '<div class="member-cost__total__shop">';
		$html .= '<span class="name">登録なし</span>';
		$html .= '</div>';
		$html .= '<div class="member-cost__total__price">¥0</div>';
		$html .= '</div>';
		$html .= '</div>';
	}
return $html ;
}

/**
* show_member_list
* register.phpのmember用レコード生成
* @return $html
*/
function show_member_list(){
$member_terms = get_terms( 'member', array( 'hide_empty'=>false));
$html = '';
$get_member = $_GET['member'];
foreach($member_terms as $term){
$term_name = $term->name;
$checked = '';//初期化
if($get_member == $term_name){
$checked = 'checked="checked"';
}
$html .= '<label>';
    $html .= '<input type="radio" name="member" value="'. $term_name . '" '. $checked .'>'. $term_name .'';
    $html .= '</label>';
}
return $html;
}

/**
* show_flash_message
* show_flash_messageのレコード生成
* @return $html
*/
function show_flash_message(){
	if(isset($_SESSION['flash_message']) && !empty($_SESSION['flash_message'])){
		$html = $_SESSION['flash_message'];
		unset($_SESSION['flash_message']);
		return $html;
	}
}

/**
* show_member_cost_pay_post
* 精算機能のレコード生成
* @return $html
*/
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
			if($members[0]["price"] < $members[1]["price"]){
				$html ='';
				$html = '<span class="member-cost__total__name">'.$members[0]["name"].'</span><span class=member-cost__total__arrow>→</span><span class="member-cost__total__name">'.$members[1]["name"].'</span><span class="member-cost__total__cost">¥'.number_format($members[0]["pay"]).'</span>'; 
			}elseif($members[1]["price"] < $members[0]["price"]){
				$html ='';
				$html = '<span class="member-cost__total__name">'.$members[1]["name"].'</span><span class=member-cost__total__arrow>→</span><span class="member-cost__total__name">'.$members[0]["name"].'</span><span class="member-cost__total__cost">¥'.number_format($members[0]["pay"]).'</span>'; 
			}else{
				$html ='';
				$html = '支払い必要なし';
			}
		}
		$i++;
	}
	return $html;
}

/**
* show_header_btn
* トップページheaderbtnレコード生成
* @return $html
*/
function show_header_btn(){
	$html ='';
	if(is_front_page() || is_home()){
		$html .= '<a href="'.get_template_directory_uri().'/pay.php'.'" class="back-btn">精算する</a>';
	}else{
		$html .= '<a href="'.home_url('/').'" class="back-btn">TOP</a>';
	}
	return $html;
}
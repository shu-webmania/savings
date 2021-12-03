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
	$html .= '<span class="cost__last">¥' . $totalLast .'</span>';
	$html .= '<span class="cost__current">¥' . $totalCurrent . '</span>';
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
	$post_by_term = new WP_Query($kind_args);  
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
	$user = wp_get_current_user(); //現在のログイン中ユーザー情報取得
	$userName = $user->display_name; //ユーザー名    
	$member_terms = get_terms( 'member', array( 'hide_empty'=>false)); //メンバーターム取得
	foreach( $member_terms as $term){
		$term_core = split_terms($term);
		if($userName == $term_core[1]){
			return get_member_html($term->name);
		}
	} 
}


/**
 * show_member_html
 * メンバーレコード生成
 * @param string
 * $name:メンバー名
 * @return $html
 */
function get_member_html($name){
	$html = '';
	$html .= '<span class="member__name">' . $name . '/' . '</span>';
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
		$html .= get_cost_html('登録なし','0','0');
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
		$html .= get_cost_html('登録なし','0','0');
		$html .= get_cost_html('登録なし','0','0');
		return $html;
	}
}
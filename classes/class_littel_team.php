<?php
/**
 * little team Class
 */

if ( ! defined( 'ABSPATH' ) ) exit; //direct call

if( ! class_exists( 'little_team' ) ) {
		
	class little_team
	{

		public function __construct()
		{
			add_shortcode( 'show-my-little-team', array( $this, 'little_team_team_show_my_little_team_shortcode' ) );
			add_action('init', array( $this, 'littel_team_register_post_type' ) );
			add_action('init',  array( $this, 'littel_team_register_taxonomes' ));
			add_action('admin_init', array( $this, 'little_team_extra_fields' ) , 1);
			add_action('save_post', array( $this, 'little_team_fields_update' ) , 0); 
			
		}

		public function littel_team_register_post_type(){
		
			register_post_type('little_team', array(
				'label'  => null,
				'labels' => array(
					'name'               => 'Моя команда', 
					'singular_name'      => 'Член команди', 
					'add_new'            => 'Додати новенького', 
					'add_new_item'       => 'Додавання новенького', 
					'edit_item'          => 'Редарування члена команди', 
					'new_item'           => 'Новий член команди', 
					'view_item'          => 'Переглянути', 
					'search_items'       => 'Шукати члена команди',
					'not_found'          => 'Не знайдено', 
					'not_found_in_trash' => 'Не знайдено в корзині',
					'menu_name'          => 'Little Team',
				),
				'description'         => 'Ще один плагін',
				'public'              => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false, 
				'show_in_rest'        => true,
				'menu_position'       => 1,
				'menu_icon'           => 'dashicons-groups', 
				'supports'            => array('title','editor','thumbnail'), // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
				//'taxonomies'          => array('position', 'organization'),

			) );	
		}
			
		public function littel_team_register_taxonomes(){

			register_taxonomy('position', array('little_team'), array(

				'labels'                => array(
					'name'              => 'Вакансія',
					'singular_name'     => 'Вакансія',
					'search_items'      => 'Шукати вакансію',
					'all_items'         => 'Усі вакансії',
					'view_item '        => 'Переглянути вакансії',
					'parent_item'       => 'Батьківська вакансія',
					'parent_item_colon' => 'Батьківська вакансія:',
					'edit_item'         => 'Редагувати вакансію',
					'update_item'       => 'Оновити вакансію',
					'add_new_item'      => 'Додати вакансію',
					'new_item_name'     => 'Імя вакансії',
					'menu_name'         => 'Вакансія',
				),
				'public'                => true,
				'show_in_rest'          => true, // добавить в REST API
				'hierarchical'          => false,
				'update_count_callback' => '',
				'rewrite'               => true,
				'hierarchical'          => true

			) );

			register_taxonomy('organization', array('little_team'), array(

				'labels'                => array(
					'name'              => 'Організація',
					'singular_name'     => 'Організація',
					'search_items'      => 'Шукати організацію',
					'all_items'         => 'Усі організації',
					'view_item '        => 'Переглянути організацію',
					'parent_item'       => 'Батьківська організація',
					'parent_item_colon' => 'Батьківська організація:',
					'edit_item'         => 'Редагувати організацію',
					'update_item'       => 'Оновити організацію',
					'add_new_item'      => 'Додати організацію',
					'new_item_name'     => 'Імя організації',
					'menu_name'         => 'Організація',
				),
				'public'                => true,
				'show_in_rest'          => true, // добавить в REST API
				'hierarchical'          => false,
				'update_count_callback' => '',
				'rewrite'               => true,
				'hierarchical'          => true
				
			) );
		}

		public function little_team_extra_fields() {
			
			add_meta_box( 'extra_fields', 'Кастомні поля (Мета поля)',  array( $this, 'little_team_extra_fields_box_func' ), 'little_team', 'normal', 'high'  );
		}

		public function little_team_extra_fields_box_func( $post ){

			$extra_fields .= '<p><label>Зарплата ($): <input type="number" name="extra[sall]" value="' . get_post_meta($post->ID, 'sall', 1) . '" style="width:20%"></label></p>';
			$extra_fields .= '<p><label>День народження: <input type="date" name="extra[birthday]" value="' . get_post_meta($post->ID, 'birthday', 1) . '" style="width:20%"></label></p>';
			$extra_fields .= '<input type="hidden" name="extra_fields_nonce" value="' . wp_create_nonce(__FILE__) . '">';
			
			echo $extra_fields;
			
		}
		
		
		public function little_team_fields_update( $post_id ){
		
			if( ! wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__) ) return false; 
			if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return false; 
			if( ! current_user_can('edit_post', $post_id) ) return false; 

			if( !isset($_POST['extra']) ) return false;

			$_POST['extra'] = array_map('trim', $_POST['extra']);
			foreach( $_POST['extra'] as $key=>$value ){
				if( empty($value) ){
					delete_post_meta($post_id, $key);
					continue;
				}

				update_post_meta( $post_id, $key, $value );
			}

			return $post_id;
		}
		
		/**
		 * Custom Shortcode
		 * @since    1.0.0
		 */
		public function little_team_team_show_my_little_team_shortcode( $atts = array() ) {
		
			global $post;
			global $out_posts;
			
			$atts = shortcode_atts( array(
			
				'style'   => 'grid',
				'total_members' => 10,
				
			), $atts );
			
			$args = array(
			
				'post_type' => 'little_team',
				'post_status' => 'publish',
				'posts_per_page' => $atts['total_members'],
			);
				
			$out_posts = get_posts( $args );
		
				
			if( $atts['style'] == 'grid' ){
				load_template( LITTLE_TEAM_PLUGIN_DIR . '/templates/grid_template.php' , false );
			}elseif( $atts['style'] == 'masonry' ){
				load_template( LITTLE_TEAM_PLUGIN_DIR . '/templates/masonry_template.php' , false );
			}else{
				load_template( LITTLE_TEAM_PLUGIN_DIR . '/templates/grid_template.php' , false );
			}

			wp_reset_postdata();

		}

	}
}

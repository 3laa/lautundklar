<?php
	if ( ! defined( 'ABSPATH' ) ) {die;}
	
	class PostTypeAdminGenerator
	{
		private $textDomain;
		private $postTypeId;
		private $postTypeName;
		private $itemTitleSingular;
		private $itemTitlePlural;
		private $customColumns;
		private $shortcodeTag;
		public $args;
		
		public function __construct($postTypeId, $postTypeName, $itemTitleSingular, $itemTitlePlural, $textDomain, $shortcodeTag)
		{
			$this->postTypeId = $postTypeId;
			$this->postTypeName = $postTypeName;
			$this->itemTitleSingular = $itemTitleSingular;
			$this->itemTitlePlural = $itemTitlePlural;
			$this->textDomain = $textDomain;
			$this->shortcodeTag = $shortcodeTag;
			$labels = array(
				'name' => __($this->postTypeName, $this->textDomain),
				'singular_name' => __($this->itemTitleSingular, $this->textDomain),
				'menu_name' => __($this->postTypeName, $this->textDomain),
				'name_admin_bar' => __($this->postTypeName, $this->textDomain),
				'add_new' => __('Add New', $this->textDomain),
				'add_new_item' => __('Add New '.$this->itemTitleSingular, $this->textDomain),
				'new_item' => __('New '.$this->itemTitleSingular, $this->textDomain),
				'edit_item' => __('Edit '.$this->itemTitleSingular, $this->textDomain),
				'view_item' => __('View '.$this->itemTitleSingular, $this->textDomain),
				'all_items' => __('All '.$this->itemTitlePlural, $this->textDomain),
				'search_items' => __('Search '.$this->itemTitlePlural, $this->textDomain),
				'not_found' => __('No '.$this->itemTitlePlural.' found.', $this->textDomain),
				'not_found_in_trash' => __('No '.$this->itemTitlePlural.' found in Trash.', $this->textDomain),
			);
			$this->args = array(
				'labels' => $labels,
				'public' => false,
				'publicly_queryable' => true,
				'show_ui' => true,
				'exclude_from_search' => true,
				'show_in_nav' => true,
				'query_var' => true,
				'hierarchical' => false,
				'supports' => array('title'),
				'has_archive' => false,
				'show_in_nav_menus' => false,
				'menu_position' => 20,
				'show_in_admin_bar' => true,
				'menu_icon' => 'dashicons-image-rotate',
				'rewrite' => false
			);
		}
		
		public function registerPostType()
		{
			register_post_type($this->postTypeId, $this->args);
		}
		
		public function setCustomColumns($customColumns)
		{
			$this->customColumns = $customColumns;
		}
		
		public function getCustomColumns($columns)
		{
			$columns = array('cb' => $columns['cb']);
			return array_merge($columns, $this->customColumns);
		}
		
		public function managePostsCustomColumn($column_name, $id)
		{
			if ($column_name === 'shortcode')
			{
				echo '['.$this->shortcodeTag.' id="' . $id . '"]';
			}
		}
		
		public function adminEnqueueScripts()
		{
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
		}
		
		public function run()
		{
			if (count($this->customColumns))
			{
				add_filter('manage_'.$this->postTypeId.'_posts_columns', [$this, 'getCustomColumns'], 10);
				add_action('manage_posts_custom_column', [$this, 'managePostsCustomColumn'], 10, 2);
			}
			add_action( 'init', [ $this, 'registerPostType' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueScripts' ] );
		}
	}
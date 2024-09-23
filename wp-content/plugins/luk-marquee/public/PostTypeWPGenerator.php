<?php
	
	class PostTypeWPGenerator
	{
		private $postType;
		private $shortcodeTag;
		private $head = '';
	
		public function __construct($postType, $shortcodeTag)
		{
			$this->postType = $postType;
			$this->shortcodeTag = $shortcodeTag;
		}
		
		
		public function singleTemplate($single)
		{
			global $post;
			if ($post->post_type == $this->postType)
			{
				$singleTemplate = plugin_dir_path(__FILE__) . 'templates/'.$this->postType.'_single.php';
				if (file_exists($singleTemplate))
				{
					return $singleTemplate;
				}
				return $single;
			}
			return $single;
		}
		
		
		public function addShortcode()
		{
			ob_start();
			add_shortcode($this->shortcodeTag, [$this, 'addShortcodeCallback']);
			return ob_get_clean();
		}
		
		public function addShortcodeCallback($shortcodeTags)
		{
			ob_start();
			include(plugin_dir_path(__FILE__) . 'templates/'.$this->postType.'_shortcode.php');
			return ob_get_clean();
		}
		
		public function run()
		{
			add_filter('single_template', [$this, 'singleTemplate']);
			add_action('init', [$this, 'addShortcode']);
		}
	}
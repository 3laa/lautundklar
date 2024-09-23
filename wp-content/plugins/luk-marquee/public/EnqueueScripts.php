<?php
	
	class EnqueueScripts
	{
		private $priority;
		public $wpStyles;
		public $wpScripts;
		public $adminStyles;
		public $adminScripts;
		
		
		public function __construct($priority = 999)
		{
			$this->priority = $priority;
		}
		
		public function addStyles($type, $handle, $src = '', $deps = array(), $ver = false, $media = 'all')
		{
			$style = ['handle'=>$handle, 'src'=>$src, 'deps'=>$deps, 'ver'=>$ver, 'media'=>$media];
			if ($type == 'wp') $this->wpStyles[$handle] = $style;
			else $this->adminStyles[$handle] = $style;
		}
		
		public function addScripts($type, $handle, $src = '', $deps = array(), $ver = false, $in_footer = false)
		{
			$script = ['handle'=>$handle, 'src'=>$src, 'deps'=>$deps, 'ver'=>$ver, 'in_footer'=>$in_footer];
			if ($type == 'wp') $this->wpScripts[$handle] = $script;
			else $this->adminScripts[$handle] = $script;
		}
		
		public function enqueueWP()
		{
			foreach ($this->wpStyles as $style)
			{
				wp_enqueue_style($style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media']);
			}
			foreach ($this->wpScripts as $script)
			{
				wp_enqueue_script($script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer']);
			}
		}
		
		public function enqueueAdmin()
		{
			foreach ($this->adminStyles as $style)
			{
				wp_enqueue_style($style['handle'], $style['src'], $style['deps'], $style['ver'], $style['media']);
			}
			foreach ($this->adminScripts as $script)
			{
				wp_enqueue_script($script['handle'], $script['src'], $script['deps'], $script['ver'], $script['in_footer']);
			}
		}
		
		public function run()
		{
			add_action('wp_enqueue_scripts', [$this, 'enqueueWP'], $this->priority);
			add_action('admin_enqueue_scripts', [$this, 'enqueueAdmin'], $this->priority);
		}
	}
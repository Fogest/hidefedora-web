<?php
	class Page {
		/**
		 * The title of the web site
		 * @var string
		 */
		var $page_title = DEFAULT_PAGE_TITLE;
		
		/**
		 * The page header of the website
		 * @var string
		 */
		var $page_header = "";
		
		/**
		 * The ID of the page, default to 1
		 * @var int
		 */
		var $page_id = 1; // default the page id to the homepage
		
		/**
		 * Content of the secondary navigation
		 * @var string
		 */
		
		
		var $html = "";
		
		/**
		* The privilege of the currently login user
		* @var string
		*/
		var $privilege = "1";
		
		/**
		* The privilege_id of the currently login user
		* @var string
		*/
		var $view = "";
		
		
		/**
		* This is to set the subtitle of this page
		* @var string
		*/
		var $subtitle = "";
		
		
		
		
		public function __construct() {}
		
		public function display() {
			
		}
		
		public function displayHeader() {
			
		}
		
		private function displayBody() {
			
		}
		
		private function displayContent() {
			echo $this->html;
		}
		
		private function displayFooter() {
			
		}
	}
?>
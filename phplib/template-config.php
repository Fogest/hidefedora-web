<?php
	/* Default Site Properties */
	define("SITE_URL","/template/");
	define("DEFAULT_SITE_NAME", "Template");
	define("DEFAULT_PAGE_TITLE", "Home");
	define("META_DESCRIPTION", "Template page for easily creating new websites");
	
	/* Paths */
	define("CSS_PATH", SITE_URL."css/");
	define("JAVASCRIPT_PATH",SITE_URL."js/");
	define("HTML_PATH",SITE_URL."html/");
	define("IMAGE_PATH",SITE_URL."img/");
	
	/* File Extensions */
	define("CSS_EXTENSION", ".css");
	define("JAVASCRIPT_EXTENSION", ".min.js");
	
	/* Template Options */
	define("LOGIN_REGISTER", true); // Whether to use a login/register system.
	
	//Comment the header lines to use a text based header logo instead of using an image.
	define("LOGO_WIDTH_HEADER",200);
	define("LOGO_HEIGHT_HEADER",50);
	
	define("LOGO_WIDTH_FOOTER",200);
	define("LOGO_HEIGHT_FOOTER",50);
	
	
	/* Database */
	define("DB_HOSTNAME", "localhost");
	define("DB_USERNAME", "root");
	define("DB_PASSWORD","");
	define("DB_DATABASE","template");
?>
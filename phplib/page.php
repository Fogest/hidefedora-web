<?php

class Page
{

    /**
     * The title of the web site
     * @private string
     */
    public $page_title = DEFAULT_PAGE_TITLE;

    /**
     * The page header of the website
     * @private string
     */
    public $page_header = "";

    /**
     * The ID of the page, default to 1
     * @private int
     */
    public $page_id = 1; // default the page id to the homepage
    
    /**
     * Content of the secondary navigation
     * @private string
     */
    public $html = "";

    /**
     * The privilege of the currently login user
     * @private int
     */
    public $privilege = 0;

    /**
     * The privilege_id of the currently login user
     * @private string
     */
    public $view = "";

    /**
     * This is to set the subtitle of this page
     * @private string
     */
    public $subtitle = "";

    private $alert;

    public function __construct ()
    {
        $this->alert = new Alert();
    }

    public function display ()
    {
        $this->displayHeader();
        $this->displayBody();
        $this->displayFooter();
    }

    private function displayHeader ()
    {
        header('Content-type: text/html; charset=utf-8');
        echo '<!DOCTYPE html>
				<html lang="en">
				<head>
					<meta charset="utf-8">
					<title>' . $this->page_title . ' | ' . DEFAULT_SITE_NAME . '</title>
					<meta name="description" content="' . META_DESCRIPTION . '">
					<meta name="author" content="Fog Productions">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,800" rel="stylesheet" type="text/css">
					<link rel="shortcut icon" href="'. SITE_URL .'assets/ico/favicon.png">
					<link href="' . CSS_PATH . 'bootstrap' . CSS_EXTENSION . '" rel="stylesheet">
					<link href="' . CSS_PATH . 'bootstrap-responsive' . CSS_EXTENSION . '" rel="stylesheet">
					<link href="' . CSS_PATH .
                 'main.css?ver='.$this->getCurrentGitCommit().'" rel="stylesheet">
				</head>
				<body>
				<div id="site"><div id="pageTitle" style="display: none;">', $this->page_title, '</div>
				<div class="navbar navbar-inverse">
			        <div class="navbar-inside">
			        <div class="container">
			          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			            <span class="icon-bar"></span>
			          </button>';
        if (LOGO_WIDTH_HEADER > 0)
            echo '<a class="brand" href="' . SITE_URL .
                     'index.php"><img id="header-img" src="' . IMAGE_PATH .
                     'logo.png" width="' . LOGO_WIDTH_FOOTER . '" height="' .
                     LOGO_HEIGHT_FOOTER . '" alt="' . DEFAULT_SITE_NAME .
                     '"></a>';
        else
            echo '<a class="brand" href="' . SITE_URL . 'index.php">' .
                     DEFAULT_SITE_NAME . '</a>';
        echo '
					  <div class="nav-collapse collapse">
			            <ul class="nav">
        <li><a href="' . SITE_URL . 'index.php">Home</a></li>
        ';
        if(isset($_SESSION['user_level'])) {
            if($_SESSION['user_level'] > 0) {
                echo '<li><a href="' . SITE_URL . 'html/review/review.php">Review</a></li>';
                echo '<li><a href="' . SITE_URL . 'html/unban/unban.php">Unban</a></li>';
            }
        }
        echo '<li><a href="' . SITE_URL . 'html/unban/appeal.php">Appeal Ban</a></li>';
        echo '
        </ul>';
        if (LOGIN_REGISTER) {
            echo '<div class="pull-right">';
            $this->displayRightHeader();
            echo '</div>';
        }
        echo '</div><!--/.nav-collapse -->
			        </div>
			        </div>
				</div>';
    }

    private function displayRightHeader ()
    {
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
            echo '<div class="btn-group">
					  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					    ' . $_SESSION['username'] . '
					    <span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu">
					    <li><a href="' . HTML_PATH . 'user/member.php">Profile</a></li>
			    		<li><a href="' . HTML_PATH . 'user/settings.php">Settings</a></li>
    					<li><a href="' . SITE_URL . 'logout.php">Logout</a></li>
					  </ul>
					</div>';
        } else {
            echo '<a href="#login" class="btn" role="button" data-toggle="modal"><i class="icon-globe"></i> Sign in</a>
					  <a href="#register" class="btn" role="button" data-toggle="modal"><i class="icon-pencil"></i> Register</a>';
        }
    }

    private function displayBody ()
    {
        if ($this->privilege == 0) {
            $this->displayContent();
        } elseif (isset($_SESSION['user_level']) &&
                 $_SESSION['user_level'] >= $this->privilege) {
            $this->displayContent();
        } else {
            $this->html = $this->alert->displayError("Permission Denied");
            $this->displayContent();
        }
        if (LOGIN_REGISTER)
            $this->displayModals();
    }

    private function displayContent ()
    {
        echo '<div id="mainContent" class="container"><div id="innerMain">';
        echo '<h1>' . $this->page_header . '</h1><hr/>';
        echo $this->html;
        echo '</div></div>';
    }

    private function displayModals ()
    {
        if (! isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] == false) {
            // LOGIN MODAL
            echo '<div id="login" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="true">
			      <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
				    <h3>Login</h3>
				  </div>
				  <div class="modal-body">
				    <form name="login" action="' . SITE_URL . 'index.php" method="post">
						<label>Username/Email</label>';
            if (isset($_POST['username']))
                echo '<input type="text" placeholder="Username/Email" value="' .
                         $_POST['username'] . '" name="username_login">';
            else
                echo '<input type="text" placeholder="Username/Email" name="username_login">';
            echo '			<label>Password</label>
						<input type="password" placeholder="Password" name="password_login">
                    <label><input name="rememberMe" type="checkbox"> Remember Me?</label>
				  </div>
				  <div class="modal-footer">
				    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
				    <button type="submit" id="loginBtn" class="btn btn-primary">Login</button>
				  </div>
		    	</form>
				</div>';
            
            // REGISTER MODAL
            echo '<div id="register" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="register" aria-hidden="true">
	      <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
		    <h3>Register</h3>
		  </div>
		  <div class="modal-body">
		    <form name="login" action="' . SITE_URL . 'index.php" method="post">
				<label>Username/Email</label>';
            if (isset($_POST['username']))
                echo '<input type="text" placeholder="Username/Email" value="' .
                         $_POST['username'] . '" name="username_register">';
            else
                echo '<input type="text" placeholder="Username/Email" name="username_register">';
            echo '			<label>Password</label>
				<input type="password" placeholder="Password" name="password_register">
				<label>Confirm Password</label>
				<input type="password" placeholder="Confirm Password" name="passwordConfirm_register">
				<label>Email Address</label>
				<input type="email" placeholder="Email" name="email_register">
			  </div>
			  <div class="modal-footer">
			    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
			    <button type="submit" id="registerBtn" class="btn btn-primary">Register</a>
			  </div>
	    	</form>
			</div>';
        }
    }

    private function displayFooter ()
    {
        echo '
			<div class="container">
				<div id="footer">
					<hr/>
					<div class="row">';
        if (LOGO_WIDTH_FOOTER != 0 || LOGO_HEIGHT_FOOTER != 0) {
            echo '<div class="span12 text-center">
							<a class="brand" href="index.php"><img id="footer-img" src="' .
                     IMAGE_PATH . 'logo.png" width="' . LOGO_WIDTH_FOOTER .
                     '" height="' . LOGO_HEIGHT_FOOTER . '" alt="logo"></a>
						</div>';
        }
        echo '<div id="footer-text" class="span12 text-center">
							<a href="' .
                 SITE_URL . 'index.php">Home</a> | <a href="' . HTML_PATH .
                 'footer/about.php">About</a> | <a href="' . HTML_PATH . 'footer/contact.php">Contact</a><br/>
							Copyright ' . date("Y") . ' Fog Productions (Justin)
						</div>
							
					</div>
				</div>
			</div>
				<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
                <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
				<script type="text/javascript" src="' .
                 JAVASCRIPT_PATH . 'bootstrap' . JAVASCRIPT_EXTENSION . '"></script>
                <script type="text/javascript" src="' .
                 JAVASCRIPT_PATH . 'jquery.hoverIntent.minified' . JAVASCRIPT_EXTENSION . '"></script>
                <script type="text/javascript" src="' .
                 JAVASCRIPT_PATH . 'readmore' . JAVASCRIPT_EXTENSION . '"></script>
                <script type="text/javascript" src="' .
                 JAVASCRIPT_PATH . 'script' . JAVASCRIPT_EXTENSION . '?ver='.$this->getCurrentGitCommit().'"></script>
				</div>
				</body>	
				</html>';
    }
    /**
     * Get the hash of the current git HEAD
     * We can use this to version our scripts and styles to bust caches when updates are made
     * @global CURRENT_GIT_COMMIT
     * @param str $branch The git branch to check
     * @param int $length Optionally only return the first $length characters of the hash
     * @return mixed Either the hash or a boolean false
     */
    private function getCurrentGitCommit( $branch='master', $length=false ) {
      if ( ! defined( 'CURRENT_GIT_COMMIT' ) ) {
        $hash = file_get_contents(substr($_SERVER['DOCUMENT_ROOT'], 0, -1).SITE_URL.'.git/refs/heads/'.$branch);
        define( 'CURRENT_GIT_COMMIT', ( $hash ? $hash : false ) );
      }
      return ($length ? substr(CURRENT_GIT_COMMIT, 0, $length ) : CURRENT_GIT_COMMIT );
    }
}
?>

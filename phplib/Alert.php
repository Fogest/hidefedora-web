<?php
class Alert {

	public function __construct() {
	}

	public function displayWarning ($message) {
		return '<div class="alert">
				<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
				<strong>Warning!</strong> '. $message .'
						</div>';
	}
	
	public function displayWarningBlock ($message) {
		return '<div class="alert alert-block">
		<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></button>
		<h4>Warning!</h4>
		'. $message .'
		</div>';
	}
	
	public function displayError ($message) {
		return '<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
				<strong>Error!</strong> '. $message .'
						</div>';
	}
	
	public function displayErrorBlock ($message) {
		return '<div class="alert alert-block alert-error">
		<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></button>
		<h4>Error!</h4>
		'. $message .'
		</div>';
	}
	
	public function displaySuccess ($message) {
		return '<div class="alert alert-success">
				<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
				<strong>Success!</strong> '. $message .'
						</div>';
	}
	
	public function displaySuccessBlock ($message) {
		return '<div class="alert alert-block alert-success">
		<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></button>
		<h4>Success!</h4>
		'. $message .'
		</div>';
	}
	
	public function displayInfo ($message) {
		return '<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
				 <strong>Info:</strong> '. $message .'
				</div>';
	}
	
	public function displayInfoBlock ($message) {
		return '<div class="alert alert-block alert-info">
		<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></button>
		<h4>Info:</h4>
		'. $message .'
		</div>';
	}
}
?>
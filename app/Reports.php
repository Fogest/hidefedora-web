<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model {

	protected $table = 'reports';
	public $timestamps = true;
	protected $hidden = array('comment', 'youtubeUrl');

}
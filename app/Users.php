<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model {

	protected $table = 'users';
	public $timestamps = true;
	protected $fillable = array('username', 'password', 'email');
	protected $hidden = array('password', 'account_creation_ip');

}
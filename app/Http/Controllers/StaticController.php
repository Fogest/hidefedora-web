<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class StaticController extends Controller {
	public function home() {
		return view('static.home');
	}

	public function contact() {
        return view('static.contact');
    }

    public function about() {
        return view('static.about');
    }

}

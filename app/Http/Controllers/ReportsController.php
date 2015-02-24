<?php namespace App\Http\Controllers;

use App\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ReportsController extends Controller {


    public function index() {
        if(!Auth::check())
            return view('static.denied');
        if(Auth::user()->user_level < 1)
            return view('static.denied');
        $reports = Reports::where('approvalStatus', 0)->get();
        return view('reports.index', compact('reports'));
    }

    public function history() {
        if(!Auth::check())
            return view('static.denied');
        if(Auth::user()->user_level < 1)
            return view('static.denied');
        $reports = Reports::where('approvalStatus', 1)->orWhere('approvalStatus', -1)->get();
        return view('reports.history', compact('reports'));
    }

    public function update() {
        if(!Auth::check())
            return view('static.denied');
        if(Auth::user()->user_level < 1)
            return "Error: Not logged in, or user level too low!";

        if(Input::has('status') && Input::has('id')) {
            $status = Input::get('status');
            $id = Input::get('id');
            $reports = Reports::find($id);

            if(is_null($reports))
              return "Error: Object not found.";
            $reports->approvalStatus = $status;
            $reports->approvingUser = Auth::user()->name;
            $reports->save();
            return "Success: Updated report status.";
        }
        return "Error: No status and/or id inputted";
    }
}

?>
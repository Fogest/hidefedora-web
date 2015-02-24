<?php namespace App\Http\Controllers;

use App\Reports;
use Illuminate\Support\Facades\Input;

class ReportsController extends Controller {


    public function index() {
      $reports = Reports::where('approvalStatus', 0)->get();

      return view('reports.index', compact('reports'));
    }

    public function history() {
        $reports = Reports::where('approvalStatus', 1)->orWhere('approvalStatus', -1)->get();
        return view('reports.history', compact('reports'));
    }
    public function update() {
      if(Input::has('status') && Input::has('id')) {
          $status = Input::get('status');
          $id = Input::get('id');
          $reports = Reports::find($id);

          if(is_null($reports))
              return "Error: Object not found.";
          $reports->approvalStatus = $status;
          $reports->save();
          return "Success: Updated report status.";
      }
      return "Error: No status and/or id inputted";
    }
}

?>
<?php namespace App\Http\Controllers;

use App\Reports;
use Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ReportsController extends Controller {


    public function index() {
        if(!Auth::check())
            return view('static.denied');
        if(Auth::user()->user_level < 1)
            return view('static.denied');
        $reports = Reports::where('approvalStatus', 0)->take(100)->get();
        return view('reports.index', compact('reports'));
    }

    public function history() {
        if(!Auth::check())
            return view('static.denied');
        if(Auth::user()->user_level < 1)
            return view('static.denied');
        $reports = Reports::where('approvalStatus', 1)->orWhere('approvalStatus', -1)->take(100)->get();
        return view('reports.history', compact('reports'));
    }

    public function create() {
        return view('reports.create');
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

    public function store() {
        $input = Request::all();

        $checkID = $this->checkIfIdValid($input['profileUrl']);
        if(!$checkID)
            return $checkID;

        $regex = "/\d+/";
        $id = array();
        if(!preg_match_all($regex,$input['profileUrl'],$id))
            return 'Error finding ID in url.';
        $id = $id[0][0];

        //Get profile data from Google+, or die if it doesn't exist.
        $profileData = $this->fetchProfileInfo($id);
        if(!$profileData)
            return 'Profile does not exist';

        $report = Reports::where('profileId',$id)->get()->first();
        if(is_null($report)) {
            //There is no existing report, make new one.
            $report = new Reports();
            $report->profileId = $id;
            if(isset($input['comment']))
                $report->comment = $input['comment'];

            $regex = "/(https|http):\/\/(www.)?youtube.com\/.+/";
            if(isset($input['youtubeUrl']) && preg_match($regex, $input['youtubeUrl']))
                $report->youtubeUrl = $input['youtubeUrl'];
            $report->displayName = $profileData['displayName'];
            $report->profilePictureUrl = substr($profileData['image']['url'], 0, -2) . '150';
        } else {
            //A report exists, update the report counter.
            //dd($report);
            if($report->approvalStatus != 0)
                return 'Profile has already been reviewed.';

            $report->rep++;

        }
        $report->save();
        $message = 'Successfully submitted report';
        return view('reports.create', compact('message'));
    }

    public function getJson() {
        $reports = Reports::where('approvalStatus', 1)->get();
        $fedoras = array();
        foreach ($reports as $report) {
            $fedoras[] = $report->profileId;
        }

        $jsonOutput = array("fedoras" => $fedoras);
        return json_encode($jsonOutput);
    }

    private function fetchProfileInfo($id) {
        $jsonurl = "https://www.googleapis.com/plus/v1/people/". $id ."?key=".getenv('GOOGLE_PLUS_API_KEY');
        //use @ to surpress warning.
        $json = @file_get_contents($jsonurl);
        if(!$json)
            return false;
        //Convert JSON to an array
        $data = json_decode($json,true);
        return $data;
    }

    private function checkIfIdValid($profileUrl) {
        $regex = "/((https|http):\/\/plus\.google\.com\/\d+)|(^\d+$)/";
        $profileurl = $profileUrl;
        //Kill execution if field empty or not valid id.
        if(!isset($_POST['profileUrl']))
            return 'Profile URL not filled.';
        else if(trim($profileurl) == '')
            return 'Profile URL not filled.';
        else if(!preg_match($regex,$profileurl))
            return 'URL must be from YouTube or Google+';

        return true;
    }
}

?>

<?php namespace App\Http\Controllers;

use App\Reports;
use Illuminate\Support\Facades\Cache;
use Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ReportsController extends Controller {


    /**
     * Displays the index page for the reports.
     *
     * The page is an authenticated page for any user with level 1 and above user_level.
     * The page is accessed via the "View Reports" tab at the top and allows the authenticated users
     * to approve and reject various reports.
     *
     * @return \Illuminate\View\View The index view.
     */
    public function index() {
        if(!Auth::check())
            return view('static.denied');
        if(Auth::user()->user_level < 1)
            return view('static.denied');
        $reports = Reports::where('approvalStatus', 0)->orderBy('rep', 'DESC')->orderBy('created_at', 'ASC')->take(100)->get();
        $num = Reports::where('approvalStatus', 0)->count();
        return view('reports.index', compact('reports', 'num'));
    }

    /**
     * Displays the history page for reports.
     *
     * Shows a history of the last 100 reports that have been reviewed (both approved, and rejected).
     * This is an authenticated page for users level 1 and above, and also contains an "undo" button allowing
     * the user to send a report back to the review queue.
     *
     * @return \Illuminate\View\View The history view.
     */
    public function history() {
        if(!Auth::check())
            return view('static.denied');
        if(Auth::user()->user_level < 1)
            return view('static.denied');
        $reports = Reports::where('approvalStatus', 1)->orWhere('approvalStatus', -1)->orderBy('updated_at', 'DESC')->take(100)->get();
        return view('reports.history', compact('reports'));
    }

    /**
     * Simply displays the creation view for the "Create Report" tab.
     *
     * @return \Illuminate\View\View Create page view.
     */
    public function create() {
        return view('reports.create');
    }

    /**
     * Allows updating of the status of the report.
     *
     * @return \Illuminate\View\View|string An error or success message is returned.
     */
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

            // Forget JSON cache so that new one can be generated. Only forget cache if being approved,
            // or sent back to review queue
            if($status == 1 || $status == 0)
                Cache::forget('blockedUsersJson');
            return "Success: Updated report status.";
        }
        return "Error: No status and/or id inputted";
    }

    /**
     * Stores/updates a record based on the "create report" pages data.
     *
     * When a user submits a report it is analyzed here. It checks if the profile exists as well
     * as using the Google+ API to verify that it is a valid profile. From here the report weight is incremented
     * if the report already exists, or a new report is created.
     *
     * @return bool|\Illuminate\View\View|string
     */
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
        // Forget JSON cache so that a new one can be generated.
        Cache::forget('blockedUsersJson');
        $message = 'Successfully submitted report';
        return view('reports.create', compact('message'));
    }

    /**
     * Returns JSON output of the approved reported users.
     *
     * The JSON outputted is retrieved from cache (redis) if possible, and if it is not possible,
     * then the database is queried, and the json is stored in redis again. The JSON is then outputted.
     *
     * @return mixed JSON output
     */
    public function getJson() {
        $json = Cache::rememberForever('blockedUsersJson', function()
        {
            $reports = Reports::where('approvalStatus', 1)->get();
            $fedoras = array();
            foreach ($reports as $report) {
                $fedoras[] = $report->profileId;
            }

            $jsonOutput = array("fedoras" => $fedoras);
            return json_encode($jsonOutput);
        });

        return $json;
    }


    /**
     * Simply to check if cache(redis) is working correctly.
     */
    public function checkCache() {
        echo "Redis cache contents of blockedUsersJson: \r\n";
        echo Cache::get('blockedUsersJson');
    }

    /**
     * Gets the Google+ profile data from the Google+ API.
     *
     * Uses the GOOGLE_PLUS_API_KEY to check Google+ API.
     *
     * @param $id The id to be checked.
     * @return bool|mixed False if failed to get, otherwise an array is returned.
     */
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

    /**
     * Runs a few checks to determine if the profile url given is valid or not.
     *
     * @param $profileUrl The user inputted profile URL.
     * @return bool|string String returned if invalid; True returned if valid.
     */
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

<?php namespace App\Http\Controllers;

use App\Appeal;
use App\Reports;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Request;

class AppealController extends Controller {

	public function index() {
        return view('appeals.index');
    }

    public function store() {

        if(Request::has('profileId') && Request::has('comment')) {
            $id = Request::input('profileId');
            $comment = Request::input('comment');
        } else {
            return view('appeals.index', array(
                'message' => "You must input a comment and profile id",
                'status' => "error"
            ));
        }
        $appeal = Appeal::where('profileId',$id)->get()->first();
        $appealId = 0;

        if(is_null($appeal)) {
            if(!is_null(Reports::where('profileId',$id)->get()->first())) {
                $appeal = new Appeal();

                $appeal->profileId = $id;
                $appeal->comment = $comment;
                if (Request::has('email'))
                    $appeal->email = Request::input('email');
                $appeal->save();
                $appealId = $appeal->id;

                $url = getenv('BASE_URL') . "/appeal/$id";
                Mail::send('appeals.notifyEmail', compact('url'), function ($message) {
                    $message->from('admin@jhvisser.com', "Hide Fedora Staff")->subject("A ban appeal has been created!");;

                    if(Request::has('email'))
                        $message->to(Request::input('email'))->cc('admin@jhvisser.com');
                    else
                        $message->to('admin@jhvisser.com');
                });

            } else {
                $contactPageUrl = getenv('BASE_URL') . "/contact";
                return view('appeals.index', array(
                    'message' => "This profile id does not exist in our records! If this is an error please send
                    us an email via our <a href=\"".$contactPageUrl."\" class=\"alert-link\">contact page</a>.",
                    'status' => "error"
                ));
            }
        } else {
            $appealId = $appeal->id;
            $appealUrl = getenv('BASE_URL') . "/appeal/" . $appealId;
            $contactPageUrl = getenv('BASE_URL') . "/contact";
            return view('appeals.index', array(
                'message' => "This profile has already been appealed before. If you feel we made an error in our appeal
                please use the <a href=\"".$contactPageUrl."\" class=\"alert-link\">contact page</a> to request further help.
                The appeal can be found <a href=\"".$appealUrl."\" class=\"alert-link\">here</a>.", 'status' => "error"));
        }

        $appealUrl = getenv('BASE_URL') . "/appeal/" . $appealId;
        return view('appeals.index', array('message' => "Your appeal has been successfully made! We will get back to you soon!
        You can view your appeal <a href=\"".$appealUrl."\" class=\"alert-link\">here</a>."));
    }

    public function show($id) {
        $appeal = Appeal::find($id);
        if(is_null($appeal)) {
            return view('appeals.show');
        }
        return view('appeals.show', $appeal->toArray());
    }

    public function update($id) {
        if(Request::has('response') && Request::has('status')) {
            $appeal = Appeal::find($id)->first();
            $appeal->response = Request::input('response');

            $status = Request::input('status');
            if($status == 0 || $status == 1) {
                $appeal->status = 1;
                $report = Reports::where('profileId',Request::input('profileId'))->get()->first();
                if(!is_null($report)) {
                    $report->approvalStatus = -1;
                    $report->approvingUser = Auth::user()->name;
                    $report->save();
                    Cache::forget('blockedUsersJson');
                } else {
                    return view('static.error', array(
                        'message' => "There was an error changing the approval status.",
                        'status' => "error"
                    ));
                }
            } elseif ($status == 2 || $status == 3) {
                $appeal->status = -1;
            }

            if(!is_null($appeal->email) && ($status == 0 || $status == 2)) {
                if($status == 0)
                    $word = 'approved';
                else
                    $word = 'rejected';
                $url = getenv('BASE_URL') . "/appeal/$id";
                $response = Request::input('response');

                Mail::send('appeals.email', compact('url', 'response', 'word'), function ($message) use($appeal) {
                    $message->from('admin@jhvisser.com', "Hide Fedora Staff")->subject("Your appeal has been updated!");;

                    $message->to($appeal->email)->cc('admin@jhvisser.com');
                });
            }

            $appeal->save();
            $returnArray = $appeal->toArray();
            $returnArray['id'] = Request::input('appealId');
            return view('appeals.show', $returnArray);
        } else {
            return view('static.error', array(
                'message' => "You must input a response to submit a response. Stop being dumb admin!",
                'status' => "error"
            ));
        }
    }
}

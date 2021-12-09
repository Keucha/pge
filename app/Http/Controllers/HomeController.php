<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;

use Yajra\Datatables\Datatables;

use Mail;

use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function adminview()
    {
        //return view('home');
        return redirect('users/list');
    }

    public function userview()
    {
        return view('userview');
    }

    public function guestuserview()
    {
         return view('guestuserview');
    }


    public function SendAVE() {

      $data = array("name"=>"GoogleMapApp");

      Mail::send('mail', $data, function($message) {
        $user = Auth::user();
         $message->to($user->email, $user->name)
         ->subject('Activate Your Account');

         $message->from('info@admedia-technologies.com','GoogleMapApp');
      });


   }

    public function index()
    {

        $user = Auth::user();

        if($user->user_status==1){

        if($user->role_id == 1)
        {
           return $this->adminview();
        }
        elseif($user->role_id == 2)
        {
           return $this->userview();
        }
        else{

          return  $this->guestuserview();
        }

    }else{


            $this->SendAVE();

            Auth::logout();

            return redirect('/useremail');

    }



       // return view('home');
    }

    public function userlistingview()
    {

       $data['list'] = User::where('role_id','>',1)->get();

        return view('userslist',$data);
    }

    public function userlistdatatable()
    {
        $users = User::where('role_id','>',1)->get();

        return Datatables::of($users)
        ->addColumn('status', function($query){
           return $query->user_status==1 ? 'Active' : 'Inactive';
        })
        ->addColumn('date', function($query){
           return date('m-d-Y h:i a',strtotime($query->created_at));
        })
        ->addColumn('locations', function($query){
           return '<a  href="'.url('user/location/'.$query->id.'').'">View Locations</a>';
        })
        ->rawColumns(['locations'])
        ->make();
    }

        public function userlistdatatabletwo(Request $req)
    {
        $users = DB::table('user_locations')->where('user_id','=',$req->user_id)->get();

        return Datatables::of($users)
        ->addColumn('formatted_address', function($query){
           if(empty($query->business_status) || $query->business_status=="NF")
           {
                return $query->click_event_latlng_both;
           }else
           {
                return $query->formatted_address;
           }
        })
         ->addColumn('created_at', function($query){
           return date('m-d-Y h:i a',strtotime($query->created_at));
        })
        // ->addColumn('date', function($query){
        //    return date('m-d-Y h:i a',strtotime($query->created_at));
        // })
        // ->addColumn('locations', function($query){
        //    return '<a target="_blank" href="'.url('user/location/'.$query->id.'').'">View Locations</a>';
        // })
        // ->rawColumns(['locations'])
        ->make();
    }

    public function userlocationlist($id)
    {
        $data['user_id']=$id;
        return view('userlocations',$data);
    }

    public function mapview()
    {
        return view('mapview');
    }


}

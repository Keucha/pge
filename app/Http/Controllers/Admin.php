<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\DB;

use Mail;

use Auth;
use Illuminate\Support\Facades\Hash;
use Response;
class Admin extends Controller
{
     public function adminloginview()
    {
      return view('auth/login');
    }


    public function accountverified($id)
    {

      $user = User::where('id',$id)->get();

      if($user->count() > 0)
      {

        if($user->first()->user_status > 0)
        {
          return redirect('/');
        }else{


          User::where('id',$id)->update([
            'user_status'=>1
          ]);

          return view('accountverified');

        }





      }else
      {
        return redirect('/');
      }




    }

     public function sentemailview()
    {
        return view('accountverification');
    }

    public function googledatastore(Request $req)
    {
      $posted_data = $req->posted_data;

      $posted_arr  = json_decode($posted_data,true);

      $user_id = Auth::user()->id;

      if(isset($posted_arr[0]['click_event_data']) && isset($posted_arr[0]['click_event_data']['click_event_plc']))
      {
          $resp_one = $posted_arr[0]['click_event_data'];




          if(isset($posted_arr[1]['place_info_data']))
          {
            $resp_two = $posted_arr[1]['place_info_data'];
          }
          else{
            $resp_two['business_status']="NF";
            $resp_two['formatted_address']="NF";
            $resp_two['name']="NF";
            $resp_two['vicinity']="NF";
          }


          $data_for_insert = [
            'user_id'=>$user_id,
            'click_event_lat'=>$resp_one['click_event_lat'],
            'click_event_lng'=>$resp_one['click_event_lng'],
            'click_event_place'=>$resp_one['click_event_plc'],
            'click_event_latlng_both'=>$resp_one['click_event_latlng_both'],
            'business_status'=>(isset($resp_two['business_status']) ? $resp_two['business_status'] : ''),
            'formatted_address'=>(isset($resp_two['formatted_address']) ? $resp_two['formatted_address'] : ''),
            'name'=>(isset($resp_two['name']) ? $resp_two['name'] : ''),
            'vicinity'=>(isset($resp_two['vicinity']) ? $resp_two['vicinity'] : ''),
            'created_at'=>date('Y-m-d H:i:s'),
          ];

          $check = DB::table('user_locations')
              ->where('user_id',$user_id)
              ->where('click_event_place',$resp_one['click_event_plc']);

          if($check->count() > 0)
          {
            return Response::json(array(
                    'code'=>200
                ));
          }else
          {

          $location_id = DB::table('user_locations')->insertGetId($data_for_insert);


            return Response::json(array(
                    'code'=>200

                ));
          }


      }

    }


    public function getalllocationsbyuserid(Request $req)
    {
        $user_id = Auth::user()->id;

        $query =  DB::table('user_locations')->orderBy('id','DESC')->get();



        if($query->count() > 0)
        {
          $query_two = DB::select("SELECT * FROM location_types ORDER BY id DESC");

          return Response::json(array(
                    'code'=>200,
                    'resp'=>$query,
                    'resp_two'=>$query_two
                ));
        }
        else
        {
          return Response::json(array(
                    'code'=>400,
                    'resp'=>'',
                    'resp_two'=>''
                ));
        }

    }


    public function loadlocationsatinit(Request $req)
    {
      $query =  DB::table('user_locations')->orderBy('id','DESC')->limit(5)->get();

      if($query->count() > 0)
        {


          return Response::json(array(
                    'code'=>200,
                    'resp'=>$query
                ));
        }
        else
        {
          return Response::json(array(
                    'code'=>400,
                    'resp'=>'',
                ));
        }

    }

    public function saveUser(Request $req)
    {
        $req = $req->donnee;
        if (!filter_var($req["email"], FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                "status" => "FAILED",
                "message" => "L'adresse email est invalide"
            ]);
        }
        if (User::where('email', $req["email"])->count() > 0) {
            return response()->json([
                "status" => "FAILED",
                "message" => "L'adresse email est déjà utilisée"
            ]);
        }
        if ($req["password"] == $req["repassword"]) {
            try {
                $user = new User();
                $user->name = $req["name"];
                $user->email = $req["email"];
                $user->password = Hash::make($req["password"]);
                $user->role_id = $req["role"];
                $user->user_status = $req["status"];
                $user->save();
                return response()->json([
                    "status" => "SUCCESS",
                    "message" => "L'utilisateur a été enregistré avec succès"
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    "status" => "FAILED",
                    "message" => "Une erreur inattendue s'est produite lors de l'enregistrement des informations de l'utilisateur<br>".$th
                ]);
            }
        } else {
            return response()->json([
                "status" => "FAILED",
                "message" => "Les mots de passe ne correspondent pas"
            ]);
        }
    }


}

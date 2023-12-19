<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Workshops;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use DB; 
use Carbon\Carbon; 
use Mail; 

use Illuminate\Support\Str;

class LoginController extends Controller
{

 
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request): RedirectResponse
    {
       
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::guard('front-access')->attempt(['email' => $request->email, 'password' => $request->password, 'type' => 'W','status' => 'Y'])) {
            
            $request->session()->regenerate();
            
            return redirect('/workshop/dashboard');
            //return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

  
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        return view('workshop.dashboard');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('userpanel.index');
    }

    public function profile(Request $request)
    {
        $workshopid = Auth::user()->workshopid;
        $Workshop =Workshops::select('*')->where('id', $workshopid)->get();
        $address =Address::select('*')->where('id', $Workshop[0]->addressID)->get();
        $city =City::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();
        $state =State::select('*')->where('country_id', '14')->orderBy('name','ASC')->get();

        return view('workshop.profile')->with('workshop',$Workshop)->with('Address',$address)->with('city',$city)->with('state',$state);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forgot_password(Request $request)
    {
        return view('workshop.forgot_password');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
        ]);
        $user = DB::table('users')->where([
                                'email' => $request->email
                              ])
                              ->first();
        
       
        Mail::send('mail.forgetPassword', ['token' => $token, 'name' => $user->name ], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        return back()->with('success', 'We have e-mailed your password reset link!');
    }

    public function showResetPasswordForm($token) { 
       
        return view('workshop.forget_passwordlink', ['token' => $token]);
     }

     public function submitResetPasswordForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
              'password' => 'required|string|min:6|confirmed',
              'password_confirmation' => 'required'
          ]);

          $updatePassword = DB::table('password_resets')
                              ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                              ])
                              ->first();
                              
          if(!$updatePassword){
              return back()->withInput()->with('error', 'Invalid token!');
          }
          $user = User::where('email', $request->email)
                      ->update(['password' => Hash::make($request->password)]);
          DB::table('password_resets')->where(['email'=> $request->email])->delete();
          return redirect('workshop/login')->with('success', 'Your password has been changed!');

      }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_password(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:6|string'
        ]);
        $auth = Auth::user();
 
 // The passwords matches
        if (!Hash::check($request->get('current_password'), $auth->password)) 
        {
            return back()->with('error', "Current Password is Invalid");
        }
 
// Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0) 
        {
            return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }
 
        $user =  User::find($auth->id);
        $user->password =  Hash::make($request->new_password);
        $user->save();
        return back()->with('success', "Password Changed Successfully");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       
            $request->validate([
                'business_name' => 'required|max:50',
                'email' => 'required|max:50|email',
                'phone' => 'required|max:20|min:10',
                'Contact_person' => 'required|max:50',
                'vABN' => 'required|max:50',
                'vAddressline1' => 'required|max:50',
                'branchID' => 'required',
                'stateID' => 'required',
                'cityID' => 'required',
                'postcode' => 'required|max:10',
            ]);
      
        
        $data_arry = array();
        $data_arry['business_name'] = $request->business_name;
        $data_arry['email'] = $request->email;
        $data_arry['phone'] = $request->phone;
        $data_arry['Contact_person'] = $request->Contact_person;
        $data_arry['branchID'] = $request->branchID;
        $data_arry['ABN'] = $request->vABN;
        
        
        $addresses_arry = array(); 
            // address details
        $addresses_arry['vAddressline1'] =$request->vAddressline1;
        //$addresses_arry['vAddressline2'] =$request->vAddressline2;
        $addresses_arry['stateID'] =$request->stateID;
        $addresses_arry['cityID'] =$request->cityID;
        $addresses_arry['postcode'] =$request->postcode;

           
            $recid = $request->id;
            $addressid = $request->adddressid;
            Address::where('id', decrypt($addressid))->update($addresses_arry);
            Workshops::where('id', decrypt($recid))->update($data_arry);
            \LogActivity::addToLog('workshop ' . $request->business_name . ' updated(' . decrypt($recid) . ').');
            return redirect('/workshop/profile/')->with('success', 'workshop updated successfully');
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Auth::guard('front-access')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('workshop/login');
    }
}

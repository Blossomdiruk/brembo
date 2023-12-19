<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request){
        $id = auth()->user()->id;
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id
        ]);

        auth()->user()->update($request->only('name','email'));

        if($request->input('password')){
            auth()->user()->update([
                'password'=> bcrypt($request->input('password'))
            ]);
        }

        return redirect()->route('profile')->with('success','Profile saved successfully');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserSecondary;
use App\Models\User;


class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
         return view('user.login');

    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            if(auth()->user()->user_role == 1){
            return redirect()->route('tgg-fct.admin.dashboard'); 

            }elseif(auth()->user()->user_role == 2){
            return redirect()->route('tgg-fct.researcher.dashboard'); 

            }elseif(auth()->user()->user_role == 3){
            return redirect()->route('tgg-fct.volunteer.dashboard'); 

            }
            elseif(auth()->user()->user_role == 5){
            return redirect()->route('tgg-fct.assignee.dashboard'); 

            }else{
               return back()->with('error', 'Role not found, you are not registered. Please register.');

            }

            
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function logout(Request $request)
    {
        //
        Auth::guard('web')->logout();
        return redirect()->route('tgg-fct.login');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LockScreenController extends Controller
{
  public function get()
  {

    // only if user is logged in
    if (Auth::check()) {
      session(['locked' => true]);
      return view('lockScreen');
    }

    return redirect('/login');
  }

  public function post(Request $request)
  {
    // if user in not logged in
    if (!Auth::check())
      return redirect('/login');
    $password = $request->get('password');
    if (Hash::check($password, Auth::user()->password)) {
      session(['locked' => false]);
      return redirect('/home');
    } else {
      return view('lockScreen', ['erreur' => "Le mot de pass est incorrect"]);
    }
  }
}

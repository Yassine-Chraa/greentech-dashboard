<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    if ($request->get('page') !== null) {
      $users = User::paginate(8);
      return response()->json($users);
    } else {
      if ($request->get('id') !== null) {
        $user = User::findOrFail($request->get('id'));
        $request->validate([
          'password' => 'required|min:6',
        ]);
        if (Hash::check($request->get('password'), $user->password)) {
          redirect()->route('home');
        } else {
          return response(['error' => 'Le mot de passe est incorrect'], 422);
        }
      }
    }
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|min:4',
      'email' => 'required|email',
      'password' => 'required|min:6',
    ]);

    $newUser = new User([
      'name' => $request->get('name'),
      'email' => $request->get('email'),
      'password' => Hash::make($request->get('password')),
    ]);
    $newUser->save();

    return response()->json(['message' => 'User stored']);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $user = User::findOrFail($id);
    if ($request->get('isAdmin') === null) {
      if ($request->get('nvPassword') === null) {
        $request->validate([
          'name' => 'required|min:4',
          'email' => 'required|email',
        ]);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->save();

        return response()->json(['message' => 'User updated']);
      } else {
        $request->validate([
          'password' => 'required|min:6',
          'nvPassword' => 'required|min:6',
        ]);

        if (Hash::check($request->get('password'), $user->password)) {

          $user->password =  Hash::make($request->get('nvPassword'));
          $user->save();

          return response()->json(['message' => 'User updated']);
        } else {
          return response(["error" => "Le mot de pass est incorrect"], 422);
        }
      }
    } else {
      $user->isAdmin = !$request->get('isAdmin');
      $user->save();
      return response()->json(['message' => 'User updated']);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['message' => 'User deleted']);
  }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $messages = DB::table('messages')
      ->select('messages.id', 'nom', 'numero', 'email', 'sujet', 'contenu', 'date')
      ->where('nom', 'LIKE', $request->get('keyword') . '%')
      ->where('messages.date', $request->get('date') == "" ? 'LIKE' : '=', $request->get('date'))
      ->orderBy($request->get('orderTarget'), $request->get('order'))
      ->paginate(6);
      return $messages;
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
        'nom' => 'required',
        'numero' => 'required|digits:10',
        'email' => 'email',
      ]);
      $newMessage = new Message([
        'nom' => $request->get('nom'),
        'numero' => $request->get('numero'),
        'email' => $request->get('email'),
        'sujet' => $request->get('sujet'),
        'contenu' => $request->get('contenu'),
      ]);

      $newMessage->save();

      return response()->json(['message' => 'Message stored']);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $message = Message::findOrFail($id);
      $message->delete();

      return response()->json(['message' => 'Message deleted']);
    }
}

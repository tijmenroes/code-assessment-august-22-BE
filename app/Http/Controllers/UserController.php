<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::withTrashed()->get();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'role' => 'required|in:boss,developer,designer,intern',
            'email' => 'required|email',
            'picture' => 'required',
        ]);

        $user = User::find($id);

        $user->update([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'picture' => $request->picture,
        ]);

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::find($id)->delete();
    }

    /**
     * Restore soft deleted user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $user = User::withTrashed()->find($id)->restore();
        return $user;
    }
}

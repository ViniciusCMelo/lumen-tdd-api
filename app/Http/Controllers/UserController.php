<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|max:255'
        ]);
        $user = new User($request->all());
        $user->save();
        return $user;
    }

    public function view($id) {
        return User::find($id);
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|max:255'
        ]);
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->update();
        return $user;
    }
}

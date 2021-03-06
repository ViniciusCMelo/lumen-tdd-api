<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            'password' => 'required|confirmed|max:255'
        ]);
        $user = new User($request->all());
        $user->save();
        return $user;
    }

    public function view($id) {
        return User::find($id);
    }

    public function delete($id) {
        if (User::destroy($id)) {
            return new Response('Removido com sucesso', 200);
        } else {
            return new Response('Erro ao remover!', 401);
        }
    }


    public function list() {
        return User::all();
    }

    public function update(Request $request, $id) {
        $dataValidation = [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
        ];

        if (isset($request->all()['password'])) {
            $dataValidation['password'] = 'required|confirmed|max:255';
        }
        $this->validate($request, $dataValidation);
        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if (isset($dataValidation['password'])) $user->password = $request->input('password');
        $user->update();
        return $user;
    }
}

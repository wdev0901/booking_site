<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class RoleAssignController extends Controller
{

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'role_id' => 'required'
        ]);

        $user = User::findOrFail($id);
        if ($user->is_admin != 1) {
            $user->role_id = $request->role_id;
            $user->save();
        }
        $response = [
            'message' => Lang::get('lang.user_successfully_saved')
        ];
        return response()->json($response, 202);
    }

}

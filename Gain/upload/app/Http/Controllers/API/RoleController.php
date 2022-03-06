<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class RoleController extends Controller
{
    public function allData(Request $request)
    {
        $getdata = Role::orderBy($request->columnKey, $request->columnSortedBy);


        if (empty($requestType)) {
            $count = $getdata->get()->count();
            $allData = $getdata->get();
            return ['datarows' => $allData, 'count' => $count];
        } else {
            return $getdata->orderBy($request->columnKey, $request->columnSortedBy)->get();
        }
    }

    public function allDataUser()
    {
        $getdata = Role::all('id', 'title');
        return $getdata;
    }

    public function getRolePermissions($id)
    {
        $data = Role::select('id', 'title', 'permissions')->where('id', $id)->first();
        $data['permissions'] = unserialize($data->permissions);

        return $data;

    }

    public function getRoleWithout()
    {
        $roleData = [];

        return $roleData;
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
        ]);

        $title = $request->input('title');
        $permissions = $request->input('permissions');
        $serializePermission = serialize($permissions);
        $created_by = Auth::user()->id;

        $role = new Role(
            [
                'title' => $title,
                'permissions' => $serializePermission,
                'created_by' => $created_by
            ]
        );

        if ($role->save()) {
            $role->view_schedule = [
                'href' => 'api/v1/role/' . $role->id,
                'method' => 'GET'
            ];

            $response = [
                'message' => Lang::get('lang.roles_saved_successfully'),
                'role' => $role
            ];

            return response()->json($response, 201);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $title = $request->input('title');
        $permissions = serialize($request->input('permissions'));

        $role = Role::findOrFail($id);

        $role->title = $title;
        $role->permissions = $permissions;

        if (!$role->update()) {

            return response()->json([
                'message' => Lang::get('lang.error_during_update')], 404);
        }

        $response = [
            'message' => Lang::get('lang.roles_saved_successfully'),
            'template' => $role
        ];

        return response()->json($response, 200);

    }

    public function delete($id)
    {
        $role = Role::find($id);
        $user = User::where('role_id', $role->id)->count();

        if ($user == 0) {
            $role->delete();
            return response()->json(['message' => Lang::get('lang.roles_deleted_successfully')], 201);

        } else {
            return response()->json(['message' => Lang::get('lang.error_during_deleted')], 401);
        }

    }

}

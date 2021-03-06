<?php

namespace Modules\Permission\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Permission\Model\Permission;
use Modules\Permission\Model\PermissionRole;
use Modules\Role\Model\Role;

class PermissionController extends Controller{

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }

    /**
     * @param Request $request
     *
     * @return Factory|View
     */
    public function index(Request $request){
        $permissions = Permission::with('child')->where('parent_id', 0)->orderBy('display_name', 'ASC')->get();
        $roles       = Role::orderBy('name', 'ASC')->get();

        return view('Permission::index', compact('permissions', 'roles'));
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function postUpdate(Request $request){
        $insert_data = $request->role_permission;
        PermissionRole::query()->truncate();
        foreach($insert_data as $key => $datum){
            foreach($datum as $item){
                $permission = Permission::find($item);
                if($permission->parent_id != 0){
                    if(!PermissionRole::checkRolePermission($permission->parent->id, $key)){
                        $permission_role                = new PermissionRole();
                        $permission_role->role_id       = $key;
                        $permission_role->permission_id = $permission->parent->id;
                        $permission_role->save();
                    }
                }
                $permission_role                = new PermissionRole();
                $permission_role->role_id       = $key;
                $permission_role->permission_id = $item;
                $permission_role->save();
            }
        }

        $request->session()->flash('success', trans('Access control updated successfully.'));

        return back();
    }

}

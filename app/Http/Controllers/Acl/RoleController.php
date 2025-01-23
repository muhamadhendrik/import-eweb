<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Models\Acl\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $model;
    protected $view = 'back.user-management.roles.';
    protected $route = 'user-management.roles.';
    protected $title = 'Roles';
    protected $permission_key = 'user-management-role';

    public function __construct(Role $model)
    {
        $this->model = $model;

        $this->middleware('can:' . $this->permission_key . '-create', ['only' => ['create', 'store']]);
        $this->middleware('can:' . $this->permission_key . '-view', ['only' => ['index', 'show']]);
        $this->middleware('can:' . $this->permission_key . '-update', ['only' => ['edit', 'update']]);
        $this->middleware('can:' . $this->permission_key . '-delete', ['only' => ['destroy']]);

        View::share([
            'route' => $this->route,
            'title' => $this->title,
            'view' => $this->view,
            'can' => $this->permission_key
        ]);
    }

    public function index()
    {
        $roles = Role::orderBy('id')->get();
        $acl_menus = Menu::orderBy('parent_type', 'DESC')->orderBy('ordering')->get();

        return view($this->view . 'index', compact('roles', 'acl_menus'));
    }

    public function store(Request $request)
    {
        $role = Role::updateOrCreate([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        foreach ($request->permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission
            ]);
        }

        $role->syncPermissions($request->permissions);

        // $response = [
        //     'status' => true,
        //     'message' => 'Role created successfully',
        //     'data' => $role
        // ];

        // return response()->json($response, 200);

        return redirect()->route($this->route . 'index')->with('success', 'Role created successfully!');
    }

    public function edit(Role $role)
    {
        $response = [
            'permissions' => $role->permissions->pluck('name')->toArray(),
            'role' => $role
        ];

        return response()->json($response);
    }

    public function update(Request $request, Role $role)
    {
        foreach ($request->permissions as $permission) {
            Permission::updateOrCreate([
                'name' => $permission
            ]);
        }

        Permission::whereNotIn('name', $request->permissions)->delete();

        $role->syncPermissions($request->permissions);

        // $response = [
        //     'status' => true,
        //     'message' => 'Role updated successfully',
        //     'data' => $role
        // ];

        // return response()->json($response, 200);

        return redirect()->route($this->route . 'index')->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        $role->permissions()->detach();

        $role->delete();

        $response = [
            'status' => true,
            'message' => 'Role deleted successfully',
        ];

        return response()->json($response, 200);

    }
}

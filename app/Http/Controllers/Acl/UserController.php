<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected $model;
    protected $view = 'back.user-management.users.';
    protected $route = 'user-management.users.';
    protected $title = 'Users';
    protected $permission_key = 'user-management-user';

    public function __construct(User $model)
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
        $users = User::all();
        $userCount = $users->count();
        $verified = User::whereNotNull('email_verified_at')->get()->count();
        $notVerified = User::whereNull('email_verified_at')->get()->count();
        $usersUnique = $users->unique(['email']);
        $userDuplicates = $users->diff($usersUnique)->count();

        $datas = $this->model->orderBy('id', 'desc');
        if (request()->ajax()) {
            return DataTables::of($datas)
                ->addIndexColumn()

                ->addColumn('role', function ($data) {
                    return $data->roles()->first()->name;
                })

                ->addColumn('action', $this->view . '.columns._action')
                ->rawColumns(['action'])
                ->make();
        }

        return view($this->view . 'index', [
            'totalUser' => $userCount,
            'verified' => $verified,
            'notVerified' => $notVerified,
            'userDuplicates' => $userDuplicates,
        ]);
    }

    public function create()
    {
        $roles = Role::all();
        return view($this->view . 'create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required'
        ]);

        $data = $request->only(['name', 'email']);
        $data['password'] = bcrypt($request->password);

        // Simpan user
        $user = User::create($data);

        // Assign role ke user
        $user->assignRole($request->roles);

        return redirect()->route($this->route . 'index')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        return view($this->view . 'show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view($this->view . 'edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'roles' => 'required'
        ]);

        $data = $request->only(['name', 'email']);

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        // Simpan user
        $user->update($data);

        // Assign role ke user
        $user->syncRoles($request->roles);

        return redirect()->route($this->route . 'index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route($this->route . 'index')->with('success', 'User deleted successfully');
    }

    public function changePassword()
    {
        return view($this->view . 'change-password');
    }

    public function changePasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password changed successfully');
    }
}

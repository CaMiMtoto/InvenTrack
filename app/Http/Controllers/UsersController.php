<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserCreated;
use App\Services\RoleService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Exceptions\Exception;

class UsersController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * @throws Exception
     */
    public function index(RoleService $roleService)
    {
        if (\request()->ajax()) {
            $source = User::query()
                ->select('*');
            return datatables()->of($source)
                ->addColumn('action', fn(User $user) => view('admin.users.action', compact('user')))
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $roles = $this->roleService->getAllRoles();
        $merchants = Merchant::query()->get();
        return view('admin.users.list', [
            'roles' => $roles,
            'merchants' => $merchants,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email',
                Rule::unique('users')->ignore($request->id),
            ],
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'integer', 'exists:roles,id'],
            'phone' => ['required', 'string', 'max:255'],
            'merchant_id' => [
                'nullable',
                Rule::requiredIf(auth()->user()->merchant_id != null),
                'integer', 'exists:merchants,id',
            ],
        ]);

        $id = $request->input('id');
        DB::beginTransaction();
        $random = Str::random(5);
        $values = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone'),
            'password' => Hash::make($random),
            'merchant_id' => $request->input('merchant_id'),
        ];
        if ($id > 0) {
            $user = User::find($id);
            $user->update($values);
        } else {
            $user = User::create($values);
            $user->notify(new UserCreated($user, $random));
        }
        $user->roles()->sync($request->input('roles'));


        DB::commit();
        return response()->json([
            'message' => 'User saved successfully',
            'user' => $user,
        ]);
    }

    public function toggleActive(User $user)
    {
        $user->status = strtolower($user->status) == 'active' ? 'inactive' : 'active';;
        $user->save();
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        $user->permissions()->detach();
        $user->roles()->detach();
        $user->delete();
        DB::commit();
        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

    public function show(User $user)
    {
        return $user->load('roles');
    }

    public function resetPassword(User $user)
    {
        $random = Str::random(5);
        $user->password = Hash::make($random);
        $user->save();

        $user->notify(new UserCreated($user, $random));

        return response()->json([
            'message' => 'Password reset successfully',
            'user' => $user,
        ]);

    }


}

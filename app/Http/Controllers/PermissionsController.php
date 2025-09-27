<?php

namespace App\Http\Controllers;


use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::query()->withCount('roles')
            ->latest()
            ->get();
        return view('admin.permissions.index', [
            'permissions' => $permissions
        ]);
    }
}

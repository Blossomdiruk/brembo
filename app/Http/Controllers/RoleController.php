<?php

namespace App\Http\Controllers;


use DB;
use DataTables;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use App\Models\DynamicMenu;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*
       $roles = Role::ord
,'DESC')->paginate(5);
        return view('roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
        */


        if ($request->ajax()) {
            $data = Role::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', 'roles.actions')
                ->rawColumns(['edit'])
                ->make(true);
        }

        return view('roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$permission = Permission::get();
        //return view('roles.create', compact('permission'));

        $permission = Permission::get();
        $dynamicMenu =  DynamicMenu::where('dynamic_menus.show_menu', 1)->orderBy('dynamic_menus.fOrder', 'ASC')->get();
        return view('roles.create', compact('permission', 'dynamicMenu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
            'user_manual' => 'mimes:pdf|max:2048'
        ]);

        // $role = Role::create(['name' => $request->input('name')]);

        if (!$request->file('user_manual') == "") {

            $user_manual = $request->file('user_manual')->getClientOriginalName();

            $path = $request->file('user_manual')->store('public/usermanual');
        } else {
            $path = "";
        }

        // dd($path);

        $role = new Role();
        $role->name = $request->name;
        $role->guard_name = 'web';
        $role->user_manual = $path;
        $role->save();

        $role->syncPermissions($request->input('permission'));

        \LogActivity::addToLog('New role record inserted. ID '.$role->id.'.');

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /*
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }
    */

    public function edit($id)
    {
        //$id = decrypt($id);
        $role = Role::find($id);
        $permission = Permission::get();
        $dynamicMenu =  DynamicMenu::where('dynamic_menus.show_menu', 1)->orderBy('dynamic_menus.fOrder', 'ASC')->get();

        //         print_r($dynamicMenu);
        // die();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        //  var_dump($permission); exit();
        return view('roles.edit', compact('role', 'permission', 'rolePermissions', 'dynamicMenu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
            'user_manual' => 'mimes:pdf|max:2048'
        ]);

        if ($request->hasFile('user_manual')) {

            $user_manual = $request->file('user_manual')->getClientOriginalName();

            $path = $request->file('user_manual')->store('public/usermanual');

            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->guard_name = 'web';
            $role->user_manual = $path;
            $role->save();

        } else {
            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->guard_name = 'web';
            $role->save();
        }

        \LogActivity::addToLog('Role record ID '.$role->id.' updated.');

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}

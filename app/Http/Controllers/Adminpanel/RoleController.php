<?php

namespace App\Http\Controllers\Adminpanel;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\DynamicMenu;

use DB;
use DataTables;

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
        $this->middleware('permission:role-create', ['only' => ['create', 'store', 'index']]); 
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
        $this->middleware('permission:role-list', ['only' => ['index']]);
      
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //echo "hi33"; exit();

        // $roles = Role::orderBy('id', 'DESC')->paginate(5);
        // return view('roles.index', compact('roles'))
        //     ->with('i', ($request->input('page', 1) - 1) * 5);

        if ($request->ajax()) {
            $data = Role::orderBy('id', 'DESC');
            //var_dump($data); exit();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('edit', function ($row) {

                    $edit_url = route('roles.edit', encrypt($row->id));
                    $btn = '<a href="' . $edit_url . '"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })


                ->rawColumns(['edit'])
                ->make(true);
        }

        return view('adminpanel.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        
        $permission = Permission::get();
        $dynamicMenu =  DynamicMenu::where('dynamic_menus.show_menu', 1)->orderBy('dynamic_menus.fOrder', 'ASC')->get();        
        return view('adminpanel.roles.create', compact('permission', 'dynamicMenu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

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
    public function edit($id)
    {
        $id = decrypt($id);
        $role = Role::find($id);
        $permission = Permission::get();
        $dynamicMenu =  DynamicMenu::where('dynamic_menus.show_menu', 1)->orderBy('dynamic_menus.fOrder', 'ASC')->get();

        //         print_r($dynamicMenu);
        // die();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        //  var_dump($permission); exit();
        return view('adminpanel.roles.edit', compact('role', 'permission', 'rolePermissions', 'dynamicMenu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

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

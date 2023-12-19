<?php

namespace App\Providers;

use App\Models\DynamicMenu;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Spatie\Permission\Models\Role;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        

        Schema::defaultStringLength(191);

        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            if (Auth::check()) {
                ///$menuItems = DynamicMenu::join('privilage','privilage.iFormID','=','dynamic_menus.id')->where('privilage.iUserTypeID',1)->where('dynamic_menus.show_menu',1)->where('dynamic_menus.parent_id','0')->get();
                $menuItems = DynamicMenu::where('dynamic_menus.show_menu', 1)->orderBy('parent_order', 'ASC')->get();
                
                view()->share('menuItems', $menuItems);

                // $subMenuItems = DynamicMenu::join('privilage','privilage.iFormID','=','dynamic_menus.id')->where('privilage.iUserTypeID',1)->where('dynamic_menus.show_menu',1)->where('dynamic_menus.parent_id','!=','0')->get();
                $subMenuItems = DynamicMenu::where('dynamic_menus.show_menu', 1)->where('dynamic_menus.parent_id', '!=', '0')->get();
                view()->share('subMenuItems', $subMenuItems);

                $userID = Auth::id();
                $user = User::find($userID);
              //  $roles = Role::pluck('name', 'name')->all();
              //  $userRole = $user->roles->pluck('id', 'id')->all();

                
               //var_dump($userRole);
                if($user->type == 'B'){
                    $roleID = $user->roles->first()->id;
                    $permissionHave =  DB::table('role_has_permissions')
                    ->select('permissions.dynamic_menu_id', 'dynamic_menus.parent_id')
                    ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->join('dynamic_menus', 'permissions.dynamic_menu_id', '=', 'dynamic_menus.id')
                    ->where('role_has_permissions.role_id', $roleID)
                    ->groupBy('permissions.dynamic_menu_id')
                    ->groupBy('dynamic_menus.parent_id')
                    //->orderBy('permissions.dynamic_menu_id','ASC')
                    ->get()->toArray();

                    $arrPermission = array();
                    $arrParentID = array();
                    foreach ($permissionHave as $per) {
                        $arrPermission[] = $per->dynamic_menu_id;
                        $arrParentID[] = $per->parent_id;
                    }
                    view()->share('permissionHave', $arrPermission);
                    view()->share('arrParentID', $arrParentID);

                }
               
              
            }
        });
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',
           'category-list',
           'category-create',
           'category-edit',
           'category-delete',
           'province-list',
           'province-create',
           'province-edit',
           'province-delete',
           'district-list',
           'district-create',
           'district-edit',
           'district-delete',
           'city-list',
           'city-create',
           'city-edit',
           'city-delete',
           'establishment-list',
           'establishment-create',
           'establishment-edit',
           'establishment-delete',
           'labour-office-list',
           'labour-office-create',
           'labour-office-edit',
           'letter-template-list',
           'letter-template-edit',
           'sms-template-list',
           'sms-template-edit',
           'register-complaint-create',
           'action-pending-complaint-list',
           'investigation-ongong-complaint-list',
           'temporary-closed-complaint-list',
           'complaint-status',
           'complaint-action',
           'complaint-mail',
           'complaint-calender',
           'complaint-report-upload',
           'complaint-modify',
           'complaint-view',
           'search-complaint',
           'business-nature-list',
           'business-nature-create',
           'business-nature-edit',
           'business-nature-delete',
        ];
        $dynamicID = [
            '152',
            '152',
            '152',
            '152',
            '151',
            '151',
            '151',
            '151',
            '2',
            '2',
            '2',
            '2',
            '3',
            '3',
            '3',
            '3',
            '4',
            '4',
            '4',
            '4',
            '5',
            '5',
            '5',
            '5',
            '6',
            '6',
            '6',
            '6',
            '7',
            '7',
            '7',
            '9',
            '9',
            '10',
            '10',
            '11',
            '153',
            '153',
            '153',
            '153',
            '153',
            '153',
            '153',
            '153',
            '153',
            '153',
            '13',
            '158',
            '158',
            '158',
            '158',
         ];

         for ($i=0; $i < count($permissions); $i++) {
            Permission::create([
                'name' => $permissions[$i],
                'dynamic_menu_id' => $dynamicID[$i],
               ]);
         }

    }
}

<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModulePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // 'Users' => [
            //     ['users_view', 'View Users'],
            //     ['users_create', 'Add New Users'],
            //     ['users_edit', 'Update Users'],
            //     ['users_delete', 'Delete Users'],
            //     ['users_redirect_dashboard', 'Redirect To Dashboard On Login'],
            // ],
            // 'User SHifts' => [
            //     ['user_shifts_view', 'View User Shifts'],
            //     ['user_shifts_create', 'Add New User Shifts'],
            //     ['user_shifts_edit', 'Update User Shifts'],
            //     ['user_shifts_delete', 'Delete User Shifts'],
            // ],
            // 'Schedule Work' => [
            //     ['schedule_works_view', 'View Schedule Works'],
            //     ['schedule_works_create', 'Add New Schedule Works'],
            //     ['schedule_works_edit', 'Update Schedule Works'],
            //     ['schedule_works_delete', 'Delete Schedule Works'],
            // ],
            // 'Roles' => [
            //     ['roles_view', 'View Roles'],
            //     ['roles_create', 'Add New Roles'],
            //     ['roles_edit', 'Update Roles'],
            //     ['roles_delete', 'Delete Roles'],
            // ],
            // 'Services' => [
            //     ['services_view', 'View Services'],
            //     ['services_create', 'Add New Services'],
            //     ['services_edit', 'Update Services'],
            //     ['services_delete', 'Delete Services'],
            // ],
            // 'Forms' => [
            //     ['forms_view', 'View Forms'],
            //     ['forms_create', 'Add New Forms'],
            //     ['forms_edit', 'Update Forms'],
            //     ['forms_delete', 'Delete Forms'],
            // ],
            // 'Library' => [
            //     ['libraries_view', 'View Library'],
            //     ['libraries_create', 'Add New Library'],
            //     ['libraries_edit', 'Update Library'],
            //     ['libraries_delete', 'Delete Library'],
            // ],
            // 'Courses' => [
            //     ['courses_view', 'View Courses'],
            //     ['courses_create', 'Add New Courses'],
            //     ['courses_edit', 'Update Courses'],
            //     ['courses_delete', 'Delete Courses'],
            // ],
            // 'Course Targets' => [
            //     ['course_targets_view', 'View Course Targets'],
            //     ['course_targedts_create', 'Add New Course Targets'],
            //     ['course_targets_edit', 'Update Course Targets'],
            //     ['course_targets_delete', 'Delete Course Targets'],
            // ],
            // 'Inventory' => [
            //     ['inventories_view', 'View Inventories'],
            //     ['inventories_create', 'Add New Inventories'],
            //     ['inventories_edit', 'Update Inventories'],
            //     ['inventories_delete', 'Delete Inventories'],
            //     ['building_inventories_view', 'View Building Inventories'],
            //     ['building_inventories_create', 'Add New Building Inventories'],
            //     ['building_inventories_edit', 'Update Building Inventories'],
            //     ['building_inventories_delete', 'Delete Building Inventories'],
            //     ['consumable_inventories_view', 'View Consumable Inventories'],
            //     ['consumable_inventories_create', 'Add New Consumable Inventories'],
            //     ['consumable_inventories_edit', 'Update Consumable Inventories'],
            //     ['consumable_inventories_delete', 'Delete Consumable Inventories'],
            //     ['equipment_inventories_view', 'View Equipment Inventories'],
            //     ['equipment_inventories_create', 'Add New Equipment Inventories'],
            //     ['equipment_inventories_edit', 'Update Equipment Inventories'],
            //     ['equipment_inventories_delete', 'Delete Equipment Inventories'],
            //     ['noninventory_inventories_view', 'View Non-Inventory Inventories'],
            //     ['noninventory_inventories_create', 'Add New Non-Inventory Inventories'],
            //     ['noninventory_inventories_edit', 'Update Non-Inventory Inventories'],
            //     ['noninventory_inventories_delete', 'Delete Non-Inventory Inventories'],
            // ],
            // 'Initial Overview Widget' => [
            //     ['overview_recent_logs_view', 'View Recent Logs'],
            //     ['overview_audits_view', 'View Audits'],
            //     ['overview_metrics_view', 'View Metrics'],
            //     ['overview_inventories_view', 'View Inventories'],
            //     ['overview_top_performers_view', 'View Top Performers'],
            //     ['overview_assigned_requests_view', 'View Assigned Requests'],
            //     ['overview_current_requests_view', 'View Current Requests'],
            //     ['overview_projects_view', 'View Projects'],
            //     ['overview_current_project_view', 'View Current Project'],
            // ],
            'Admin' => [],

        ];

        Permission::where('id', '>', 0)->delete();
        if (!app()->environment('testing')) {
            /* running this with refreshes migration throws PDOException: There is no active transaction.
             So lets skip this for tests  */

            // Schema::disableForeignKeyConstraints();
            // DB::statement('TRUNCATE permissions;');
            // Schema::enableForeignKeyConstraints();
            DB::statement('ALTER TABLE permissions AUTO_INCREMENT = 1'); //set the starting id to 1.
        }


        // foreach ($permissions as $module => $permission) {
        //     foreach ($permission as $p) {
        //         Permission::create([
        //             'module' => $module,
        //             'name' => $p[0],
        //             'label' => $p[1],
        //             'guard_name' => 'web'
        //         ]);
        //     }
        // }

        $permissions = json_decode(file_get_contents(storage_path('app/permissions.json')), true);
        // dd($permissions);
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(array_merge($permission, [
                'guard_name' => 'web'
            ]));
        }

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

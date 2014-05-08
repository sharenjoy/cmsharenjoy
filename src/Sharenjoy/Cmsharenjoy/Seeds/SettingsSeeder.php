<?php namespace Sharenjoy\Cmsharenjoy\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder {

    public function run()
    {
        $types = [
            [
                'key'           => 'brand_name',
                'label'         => 'Brand Name',
                'type'          => 'text',
                'value'         => 'Cmsharenjoy',
                'module'        => 'general',
                'sort'          => '1',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'key'           => 'files_enabled_providers',
                'label'         => 'Files enabled providers',
                'type'          => 'text',
                'value'         => 'local',
                'module'        => 'file',
                'sort'          => '2',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'key'           => 'files_upload_limit',
                'label'         => 'Files upload limit',
                'type'          => 'text',
                'value'         => '4',
                'module'        => 'file',
                'sort'          => '3',
                'created_at'    => date('Y-m-d H:i:s')
            ],
            [
                'key'           => 'date_format',
                'label'         => 'Date Format',
                'type'          => 'text',
                'value'         => '',
                'module'        => '',
                'sort'          => '4',
                'created_at'    => date('Y-m-d H:i:s')
            ],

        ];
        DB::table('settings')->insert($types);
        $this->command->info('Settings Table Seeded With An Example Record');

    }

}
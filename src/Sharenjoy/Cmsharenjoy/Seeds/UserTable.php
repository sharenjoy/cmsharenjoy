<?php namespace Sharenjoy\Cmsharenjoy\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class UserTable extends Seeder {

    public function run()
    {

        $types = [
            [
                'email'         => Config::get('cmsharenjoy::setup.email'),
                'password'      => Hash::make( Config::get('cmsharenjoy::setup.password') ),
                'confirmed'     => '1',
                'sort'          => '1',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]
        ];
        DB::table('users')->insert($types);
        $this->command->info('User Table Seeded');

        $types = [
            [
                'first_name'    => Config::get('cmsharenjoy::setup.first_name'),
                'last_name'     => Config::get('cmsharenjoy::setup.last_name'),
                'phone'         => Config::get('cmsharenjoy::setup.phone')
            ]
        ];
        DB::table('accounts')->insert($types);
        $this->command->info('Account Table Seeded');

    }

}

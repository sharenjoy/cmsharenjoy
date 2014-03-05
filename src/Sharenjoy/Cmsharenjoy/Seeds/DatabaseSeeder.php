<?php
namespace Sharenjoy\Cmsharenjoy\Seeds;
use Illuminate\Database\Seeder;
use Eloquent;

class DatabaseSeeder extends Seeder {

    public function run()
    {
        Eloquent::unguard();
        $this->call('Sharenjoy\Cmsharenjoy\Seeds\UserTable');
        $this->call('Sharenjoy\Cmsharenjoy\Seeds\ExampleSettingsSeeder');
        $this->command->info('All Tables Seeded');
    }

}
<?php

namespace Modules\Geography\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Geography\Entities\Geography;
use Faker\Generator as Faker;


class GeographyDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");


            $faker = \Faker\Factory::create();

            // for($i = 0; $i < 5; $i++) {
            //     Geography::create([
            //         'ar' => ['name' => 'السعودية'.$faker->name],
            //         'en' => ['name' => 'ARE'.$faker->name],
            //     ]);
            // }
            
            Geography::create([
                'ar' => ['name' => 'الإمارات'],
                'en' => ['name' => 'AUE'],
            ]);
            // Geography::create([
            //                 'ar' => ['name' => 'مصر'],
            //                 'en' => ['name' => 'EGYPT'],
            // ]);
            // Geography::create([
            //     'ar' => ['name' => 'السعودية'],
            //     'en' => ['name' => 'ARE'],
            // ]);


            for($i = 0; $i < 10; $i++) {
                Geography::create([
                    'parent_id' =>1,
                    'ar' => ['name' => 'مدينة'.$i.$i],
                    'en' => ['name' => 'city'.$i.$i],
                ]);
            }
            // for($i = 0; $i < 10; $i++) {
            //     Geography::create([
            //         'parent_id' =>2,
            //         'ar' => ['name' => 'مدينة'.$i.$i.$i],
            //         'en' => ['name' => 'city'.$i.$i.$i],
            //     ]);
            // }
            // for($i = 0; $i < 10; $i++) {
            //     Geography::create([
            //         'parent_id' =>3,
            //         'ar' => ['name' => 'مدينة'.$i],
            //         'en' => ['name' => 'city'.$i],
            //     ]);
            // }
            // }//end of foreach

    }
}

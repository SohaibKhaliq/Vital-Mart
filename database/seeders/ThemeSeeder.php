<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    { 
        \DB::table('themes')->delete();

        
        \DB::table('themes')->insert(array (
            0 => 
            array (
                'id'         => '1',
                'name'       => 'Grocery',
                'code'       => 'default',
                'is_active'  => '1',
                'is_default' => '1',
                'created_at' => '2023-09-16 12:21:37',
                'updated_at' => '2023-09-16 12:21:37',
                'deleted_at' => NULL
            ),
            array (
                'id'         => '2',
                'name'       => 'Halal Food',
                'code'       => 'halal',
                'is_active'  => '1',
                'is_default' => '0',
                'created_at' => '2023-09-16 12:21:37',
                'updated_at' => '2023-09-16 12:21:37',
                'deleted_at' => NULL
            ),
            array (
                'id'         => '3',
                'name'       => 'Furniture',
                'code'       => 'furniture',
                'is_active'  => '0',
                'is_default' => '0',
                'created_at' => '2023-09-16 12:21:37',
                'updated_at' => '2023-09-16 12:21:37',
                'deleted_at' => NULL
            ),
        )); 
    }
}
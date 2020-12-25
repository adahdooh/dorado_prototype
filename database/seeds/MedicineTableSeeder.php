<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class MedicineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('medicines')->insert([
            'name' => 'Akamol',
        ]);
        DB::table('medicines')->insert([
            'name' => 'trufien',
        ]);
        DB::table('medicines')->insert([
            'name' => 'Amecore',
        ]);
    }
}

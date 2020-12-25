<?php

use Illuminate\Database\Seeder;

class ReportCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('report_categories')->insert([
            'name' => 'Customer services',
        ]);
    }
}

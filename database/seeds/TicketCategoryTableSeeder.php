<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class TicketCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_categories')->insert([
            'name' => 'Customer services',
        ],[
            'name' => 'Dietician',
        ],[
            'name' => 'Another',
        ]);
    }
}

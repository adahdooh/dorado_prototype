<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(TicketCategoryTableSeeder::class);
//         $this->call(MedicineTableSeeder::class);
//         $this->call(CountrySeeder::class);
         $this->call(ReportCategory::class);
    }
}

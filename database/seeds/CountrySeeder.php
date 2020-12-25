<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->truncate();
        $json=File::get('database/datasets/countries.json');

        $data=json_decode($json);

        foreach ($data as $key => $obj) {
            foreach ($obj as $key2 => $obj2) {
                App\Models\Country\Country::create(array(
                    'code' => $key2,
                    'name' => $obj2,
                ));
            }
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Client::create([
            'name'=>'aya',
            'phone'=>'11111',
            'address'=>'alex',

        ]);
    }
}

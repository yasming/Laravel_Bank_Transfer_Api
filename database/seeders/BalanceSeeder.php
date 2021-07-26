<?php

namespace Database\Seeders;

use App\Models\Balance;
use App\Models\User;
use Illuminate\Database\Seeder;

class BalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Balance::create(['user_id' => User::first()->id,'amount' => 150]);
        Balance::create(['user_id' => User::last()->id, 'amount' => 100]);
    }
}

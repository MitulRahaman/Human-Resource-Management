<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            [
                'id' => 1,
                'name' => 'AB Bank Limited',
                'address' => '',
            ],
            [
                'id' => 2,
                'name' => 'Bank Asia Limited',
                'address' => '',
            ],
            [
                'id' => 3,
                'name' => 'BRAC Bank Limited',
                'address' => '',
            ],
            [
                'id' => 4,
                'name' => 'Dhaka Bank Limited',
                'address' => '',
            ],
            [
                'id' => 5,
                'name' => 'Dutch-Bangla Bank Limited',
                'address' => '',
            ],
            [
                'id' => 6,
                'name' => 'Eastern Bank Limited',
                'address' => '',
            ],
            [
                'id' => 7,
                'name' => 'Jamuna Bank Limited',
                'address' => '',
            ],
            [
                'id' => 8,
                'name' => 'Shahjalal Islami Bank Limited',
                'address' => '',
            ],
            [
                'id' => 9,
                'name' => 'Southeast Bank Limited',
                'address' => '',
            ],
            [
                'id' => 10,
                'name' => 'The City Bank Limited',
                'address' => '',
            ],
            [
                'id' => 11,
                'name' => 'HSBC Bank',
                'address' => '',
            ],
            [
                'id' => 12,
                'name' => 'The Premier Bank Limited',
                'address' => '',
            ],
            [
                'id' => 13,
                'name' => 'United Commercial Bank Limited',
                'address' => '',
            ],
            [
                'id' => 14,
                'name' => 'Sonali Bank Limited',
                'address' => '',
            ],
            [
                'id' => 15,
                'name' => 'Agrani Bank Limited',
                'address' => '',
            ],
            [
                'id' => 16,
                'name' => 'Janata Bank Limited',
                'address' => '',
            ],
            [
                'id' => 17,
                'name' => 'Rupali Bank Limited',
                'address' => '',
            ],
            [
                'id' => 18,
                'name' => 'Pubali Bank Limited',
                'address' => '',
            ],
            [
                'id' => 19,
                'name' => 'Bangladesh Development Bank Limited',
                'address' => '',
            ],
            [
                'id' => 20,
                'name' => 'Isalamic Bank Bangladesh Limited',
                'address' => '',
            ],
            [
                'id' => 21,
                'name' => 'Prime Bank Limited',
                'address' => '',
            ],
            [
                'id' => 22,
                'name' => 'Trust Bank',
                'address' => '',
            ],
            [
                'id' => 23,
                'name' => 'Standard Chartered Bank Bangladesh',
                'address' => '',
            ],
        ];

        foreach ($banks as $key=>$bank)
        {
            Bank::updateOrCreate([
                'id'=> $bank['id']
            ],
                $bank);
        }

    }
}

<?php

namespace Database\Seeders;

use App\Models\AssetType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assetTypes = [
            [
                'id' => 1,
                'name' => 'Macbook',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 2,
                'name' => 'Laptop',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 3,
                'name' => 'Keyboard',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 4,
                'name' => 'Cable',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 5,
                'name' => 'HeadPhones',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 6,
                'name' => 'Accessories',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 7,
                'name' => 'Converter',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 8,
                'name' => 'HUB',
                'status' => Config::get('variable_constants.activation.active'),
            ],[
                'id' => 9,
                'name' => 'Charger',
                'status' => Config::get('variable_constants.activation.active'),
            ],
            [
                'id' => 10,
                'name' => 'UPS',
                'status' => Config::get('variable_constants.activation.active'),
            ],
        ];

        foreach ($assetTypes as $key=>$assetType)
        {
            AssetType::updateOrCreate([
                'id'=> $assetType['id']
            ],
                $assetType);
        }
    }
}

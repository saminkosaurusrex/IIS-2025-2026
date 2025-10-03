<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShowType;
class ShowTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShowType::create(["name" => "Filmy"]);
        ShowType::create(["name" => "Prednášky"]);
        ShowType::create(["name" => "Vystúpenia"]);
    }
}

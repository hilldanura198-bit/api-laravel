<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Sanctum\PersonalAccessToken;

class ClearSanctumTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PersonalAccessToken::truncate();
    }
}
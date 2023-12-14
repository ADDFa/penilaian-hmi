<?php

namespace Database\Seeders;

use App\Models\Credential;
use Illuminate\Database\Seeder;

class CredentialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "username"  => "hmi-bengkulu",
                "password"  => password_hash("hmi-bengkulu123", PASSWORD_DEFAULT)
            ]
        ];

        foreach ($data as $value) {
            $credential = new Credential($value);
            $credential->save();
        }
    }
}

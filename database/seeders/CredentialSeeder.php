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
                "username"  => "admin",
                "password"  => password_hash("password", PASSWORD_DEFAULT)
            ]
        ];

        foreach ($data as $value) {
            $credential = new Credential($value);
            $credential->save();
        }
    }
}

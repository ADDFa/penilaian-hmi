<?php

namespace Database\Seeders;

use App\Models\AfectiveIndicator;
use Illuminate\Database\Seeder;

class AfectiveIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            "Tingkah Laku"          => [
                "Keterlambatan",
                "Tidur di dalam ruangan training",
                "Berbincang dengan teman sebelah",
                "Main Hp",
                "Posisi Duduk"
            ],
            "Tata Bahasa"           => [
                "Berbicara kotor selama LK berlangsung",
                "Menghujad peserta dan materi"
            ],
            "Pakaian dan Atribut"   => [
                "Tidak memakai peci",
                "Tidak memakai baju koko",
                "Tidak memakai sepatu (menyesuaikan kondisi forum)",
                "Tidak membawa Al-Qur'an",
                "Tidak membawa ATK",
                "Tidak memakai rok",
                "Jilbab tidak dibawah dada"
            ]
        ];

        foreach ($data as $category => $values) {
            foreach ($values as $foul) {
                $afectiveIndicator = new AfectiveIndicator([
                    "category"  => $category,
                    "foul"      => $foul
                ]);
                $afectiveIndicator->save();
            }
        }
    }
}

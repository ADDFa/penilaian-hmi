<?php

namespace Database\Seeders;

use App\Models\Foul;
use Illuminate\Database\Seeder;

class FoulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ["Keterlambatan", 5],
            ["Tidur di dalam ruangan training", 5],
            ["Berbincang dengan teman sebelah", 5],
            ["Main Hp", 5],
            ["Posisi Duduk", 5],
            ["Berbicara kotor selama LK berlangsung", 5],
            ["Menghujad peserta dan materi", 5],
            ["Tidak memakai peci", 5],
            ["Tidak memakai baju koko", 5],
            ["Tidak memakai sepatu (menyesuaikan kondisi forum)", 5],
            ["Tidak membawa Al-Qur'an", 5],
            ["Tidak membawa ATK", 5],
            ["Tidak memakai rok", 5],
            ["Jilbab tidak dibawah dada", 5],
            ["Meninggalkan Sholat 5 Waktu", 5]
        ];

        foreach ($data as $value) {
            $foul = new Foul([
                "foul"  => $value[0],
                "point" => $value[1]
            ]);
            $foul->save();
        }
    }
}

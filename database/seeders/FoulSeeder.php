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
            ["Keterlambatan", 5, "Ketepatan Waktu"],
            ["Tidur di dalam ruangan training", 5, "Tingkah Laku"],
            ["Berbincang dengan teman sebelah", 5, "Tingkah Laku"],
            ["Main Hp", 5, "Tingkah Laku"],
            ["Posisi Duduk", 5, "Tingkah Laku"],
            ["Berbicara kotor selama LK berlangsung", 5, "Tata Bahasa"],
            ["Menghujad peserta dan materi", 5, "Tata Bahasa"],
            ["Tidak memakai peci", 5, "Pakaian"],
            ["Tidak memakai baju koko", 5, "Pakaian"],
            ["Tidak memakai sepatu (menyesuaikan kondisi forum)", 5, "Pakaian"],
            ["Tidak membawa Al-Qur'an", 5, "Pakaian"],
            ["Tidak membawa ATK", 5, "Pakaian"],
            ["Tidak memakai rok", 5, "Pakaian"],
            ["Jilbab tidak dibawah dada", 5, "Pakaian"],
            ["Meninggalkan Sholat 5 Waktu", 10, "Meninggalkan Sholat"]
        ];

        foreach ($data as $value) {
            $foul = new Foul([
                "foul"      => $value[0],
                "point"     => $value[1],
                "category"  => $value[2]
            ]);
            $foul->save();
        }
    }
}

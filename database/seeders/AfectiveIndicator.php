<?php

namespace Database\Seeders;

use App\Models\AfectiveIndicator as ModelsAfectiveIndicator;
use App\Models\AfectiveIndicatorCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AfectiveIndicator extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $afectiveCategories = [
            [
                "category"  =>  "Ketepatan Waktu",
                "indicators" => [
                    [
                        "indicator" => "Keterlambatan"
                    ]
                ]
            ],
            [
                "category"  => "Tingkah Laku",
                "indicators" => [
                    [
                        "indicator" => "Tidur di dalam ruangan training"
                    ],
                    [
                        "indicator" => "Berbincang dengan teman sebelah"
                    ],
                    [
                        "indicator" => "Main Hp"
                    ],
                    [
                        "indicator" => "Posisi Duduk"
                    ],
                    [
                        "indicator" => "Meninggalkan Sholat 5 Waktu"
                    ]
                ]
            ],
            [
                "category"  => "Tata Bahasa",
                "indicators" => [
                    [
                        "indicator" => "Berbicara kotor selama LK berlangsung"
                    ],
                    [
                        "indicator" => "Menghujad peserta dan materi"
                    ]
                ]
            ],
            [
                "category"  => "Pakaian",
                "indicators" => [
                    [
                        "indicator" => "Tidak memakai peci"
                    ],
                    [
                        "indicator" => "Tidak memakai baju koko"
                    ],
                    [
                        "indicator" => "Tidak memakai sepatu (menyesuaikan kondisi forum)"
                    ],
                    [
                        "indicator" => "Tidak membawa Al-Qur'an"
                    ],
                    [
                        "indicator" => "Tidak membawa ATK"
                    ],
                    [
                        "indicator" => "Tidak memakai rok"
                    ],
                    [
                        "indicator" => "Jilbab tidak dibawah dada"
                    ]
                ]
            ]
        ];

        foreach ($afectiveCategories as $afective) {
            $category = new AfectiveIndicatorCategory([
                "category"  => $afective["category"]
            ]);
            $category->save();

            foreach ($afective["indicators"] as $indicator) {
                $indicator = new ModelsAfectiveIndicator($indicator);
                $indicator->category_id = $category->id;
                $indicator->save();
            }
        }
    }
}

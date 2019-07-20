<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            "Tissus",
            "Garniture",
            "PLAQUE",
            "SEMELLE",
            "PRODUIT TR",
            "PRODUIT PVC",
            "PU STANTDART",
        ];
        $subCatg = array(
            [
                "Cuir",
                "Standard",
                "Kercha",
                "Doubleur",
                "Croco",
                "Anzi",
                "FIAT",
                "NUBIC",
                "POLYBON",
                "GUITAGE",
                "Kercha / Eva",
                "Fiat / Eva",
                "Snobic",
                "GUITAGE"
            ],
            [
                "Metal",
                "Plastique",
                "Bzim",
                "Accessoires",
                "Gourmette",
                "STL",
            ],
            [
                "Nora",
                "EVA",
                "TEXON",
                "TEXON EPONGEE",
                "NEKRON"
            ],
            [
                "PVC",
                "TPR",
                "POLYORETHANE",
            ],
            [
                "TR",
                "PPC",
                "PVC",
                "TPR",
                "POLYORETHANE",
            ],
            [
                "PU SOUPLE",
                "PU DEMI SOUPLE",
                "PU STANTDART",
            ],
            [
                "PPC"
            ]
        );
        foreach ($categories as $cle => $value) {

            factory(App\Category::class)->create(["category_name" => $value])
                ->each(function ($c) use ($subCatg, $cle) {

//                    $col_name = array_fill(0, count($subCatg[$cle]), "sub_category");

//                    $sub = array_map(function ($el, $key) {
//                        return [$key => $el];
//                    }, $subCatg[$cle], $col_name);

//                    dd($sub);
                    if(array_key_exists($cle,$subCatg))
//                        dd($subCatg[$cle+1]);
                        foreach ($subCatg[$cle] as $val) {
                            factory(App\SubCategory::class)->create(["sub_category" => trim($val), "category_id" => $c->id]);
                        }
                });
        }


    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TotalCongeSeeder extends Seeder
{
    public function run(): void
    {
        $employes = DB::table('employes')->get();

        foreach ($employes as $employe) {
            DB::table('total_conges')->insert([
                'employe_id'        => $employe->id,
                'conge_annuel'      => 30,
                'conge_exceptionnel'=> 5,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}

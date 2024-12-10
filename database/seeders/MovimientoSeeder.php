<?php

namespace Database\Seeders;

use App\Models\Models\Movimiento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MovimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create('es_ES');
        $tarjetas = DB::table('tarjetas')->pluck('id');
        foreach ($tarjetas as $tarjeta) {
            for ($i=0; $i<=random_int(0,7); $i++) {
                Movimiento::create([
                    'tarjeta_id' => $tarjeta,
                    'cantidad' => random_int(-50, 50) * 10,
                    'fecha' => now(),
                ]);
            }
        }
    }
}

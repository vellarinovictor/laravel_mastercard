<?php

namespace Database\Seeders;

use App\Models\Tarjeta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TarjetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create('es_ES');
        $cuentas = DB::table('cuentas')->pluck('id');
        foreach ($cuentas as $cuenta) {
            for ($i=0; $i<=random_int(0,3); $i++) {
                Tarjeta::create([
                    'cuenta_id' => $cuenta,
                    'numero' => $faker->creditCardNumber('MasterCard'),
                    'limite' => random_int(3, 30) * 100,
                    'pin' => $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit(),
                    'fechavalidez' => $faker->creditCardExpirationDateString(true),
               ]);
            }
        }        
    }
}

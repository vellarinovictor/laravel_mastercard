<?php

namespace Database\Seeders;

use App\Models\Cuenta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CuentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = \Faker\Factory::create('es_ES');
        $clientes = DB::table('users')->pluck('id');
            foreach ($clientes as $cliente) {
                for ($i=1; $i<=random_int(0,3); $i++) {
                    Cuenta::create([
                        'user_id' => $cliente,
                        'numero' => $faker->iban('ES'),
                        'saldoinicial' => random_int(1, 50) * 1000,
                    ]);
                }
            }

    }
}

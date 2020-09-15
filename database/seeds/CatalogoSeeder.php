<?php

use App\Estado;
use App\FormaPago;
use Illuminate\Database\Seeder;

class CatalogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estado::insert([
            ['nombre'=>'Pendiente'],
            ['nombre'=>'Cancelado']
        ]);

        FormaPago::insert([
            ['nombre'=>'Crédito'],
            ['nombre'=>'Contado'],
        ]);
    }
}

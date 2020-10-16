<?php

use App\Cuenta;
use App\Estado;
use App\Producto;
use App\Categoria;
use App\FormaPago;
use App\Proveedor;
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

        Cuenta::insert([
            ['nombre' => 'Caja'],
            ['nombre' => 'Clientes'], 
            ['nombre' => 'Proveedores'],
            ['nombre' => 'Ventas'] 
        ]);
        
        Proveedor::insert([
            ['nombre' => 'Taller El Divino Maestro','nit' => '84642113','telefono' => '56897423','direccion' => 'Antigua Guatemala, Sacatepéquez'],
            ['nombre' => 'Ebanisteria Colonial','nit' => '8963145','telefono' => '45632114','direccion' => 'Antigua Guatemala, Sacatepéquez'],
        ]);

        Categoria::insert([
            ['nombre' => 'Ataúdes'],
            ['nombre' => 'Urnas'],
        ]);

        Producto::insert([
            ['nombre'=>'Tradicional ochavada','categoria_id'=>1, 'stock_minimo'=>2,'stock_maximo'=>5],
            ['nombre'=>'Jumbo Italiana','categoria_id'=>1, 'stock_minimo'=>2,'stock_maximo'=>5],
            ['nombre'=>'Monarca','categoria_id'=>1, 'stock_minimo'=>2,'stock_maximo'=>5],
            ['nombre'=>'Santuario','categoria_id'=>2, 'stock_minimo'=>2,'stock_maximo'=>5]
        ]);



    }
}

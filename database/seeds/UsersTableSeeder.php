<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Persona;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 
        $persona = new Persona;
        $persona->nombre = "Jope mundo";
        $persona->tipo_documento = "DNI";
        $persona->num_documento = "12345678";
        $persona->direccion = "12345678";
        $persona->telefono = "12345678";
        $persona->email = "12345678";
        $persona->save();

        $admin = new User; 
        $admin->usuario = "Ing. Jorge Denys Peralta M."; 
		$admin->password= bcrypt('123456');
        $admin->condicion = true;
        $admin->idrol = 1; 
		$admin->save();

    }
}

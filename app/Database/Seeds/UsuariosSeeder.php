<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nombre'    => 'Alejandro',
            'apellido'    => 'Pedroza',
            'telefono'    => '4772239248',
            'correo'    => 'alex.pedroza41@gmail.com',
            'direccion'    => 'Mexico',
            'clave'    => password_hash('Alex1691', PASSWORD_DEFAULT),
            'verify'    => '1',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
            'id_rol'    => 1,
        ];
        // Using Query Builder
        $this->db->table('usuarios')->insert($data);
    }
}

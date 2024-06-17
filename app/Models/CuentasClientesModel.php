<?php

namespace App\Models;

use CodeIgniter\Model;

class CuentasClientesModel extends Model
{
    protected $table = 'cuentas_clientes'; // Tu tabla
    protected $primaryKey = 'id'; // Tu clave primaria
    protected $allowedFields = ['correo', 'clave']; // Campos permitidos

    // Método para buscar un usuario por nombre de usuario
    public function findByUsername($username)
    {
        return $this->where('correo', $username)->first();
    }
}

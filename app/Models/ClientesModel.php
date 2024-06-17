<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientesModel extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['identidad', 'num_identidad', 'nombre', 'apellido', 'telefono', 'whatsapp', 'correo', 'direccion', 'foto_identificacion', 'foto_identificacion_reverso', 'foto_domicilio', 'foto_cliente', 'nombre_referencia', 'direccion_referencia', 'telefono_referencia', 'estado'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        
        'identidad' => 'required|min_length[2]',
        'num_identidad' => [
            'rules' => 'required|min_length[8]|is_unique[clientes.num_identidad,id,{id}]',
            'errors' => [
                'required' => 'El N° de Identificación es obligatorio',
                'min_length' => 'El N° de Identificacion debe contener al menos 8 caracteres',
                'is_unique' => 'La identificacion ya se encuentra registrada, favor de revisar con el cliente'
            ]
        ],
        'nombre' => 'required|min_length[3]',
        'apellido' => 'required|min_length[3]',
        'telefono' => 'required|min_length[10]|required|max_length[10]|is_unique[clientes.telefono,id,{id}]',
        'whatsapp' => 'required|min_length[10]|required|max_length[12]|is_unique[clientes.whatsapp,id,{id}]',
        'correo' => 'required|valid_email|is_unique[clientes.correo,id,{id}]',
        'direccion' => 'required|min_length[5]',
        
       



    ];
    protected $validationMessages = [];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}

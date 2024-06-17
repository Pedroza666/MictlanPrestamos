<?php

namespace App\Controllers;

use App\Models\CuentasClientesModel;
use CodeIgniter\RESTful\ResourceController;

class LoginControllerCliente extends ResourceController
{
    protected $modelName = 'App\Models\CuentasClientesModel';
    protected $format    = 'json';

    protected $cuentasClientesModel;

    public function __construct()
    {
        $this->cuentasClientesModel = new CuentasClientesModel();
    }

    public function validateLogin()
    {
        $correo = $this->request->getVar('correo');
        $clave = $this->request->getVar('clave');

        $user = $this->cuentasClientesModel->where('correo', $correo)->first();

        if ($user) {
            if (password_verify($clave, $user['clave'])) {
                return $this->respond(['status' => 'success', 'message' => 'Login successful']);
            } else {
                return $this->respond(['status' => 'error', 'message' => 'Invalid password'], 401);
            }
        } else {
            return $this->respond(['status' => 'error', 'message' => 'User not found'], 404);
        }
    }
}


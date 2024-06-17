<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use App\Models\AdminModel;

class AdminController extends BaseController
{
    private  $admin, $session;

    public function __construct()
    {
        helper(['form']);
        $this->session = session();
        $this->admin = new AdminModel();
    }

    public function index()
    {
        $data['admin'] = $this->admin->first();
        return view('admin/index', $data);
    }
    public function dashboard()
    {
        return view('admin/home');
    }

    public function update($id){
        if ($this->request->is('put')) {
            $img = $this->request->getFile('logo');
            $data = [
                'id' => $id,
                'identidad' => $this->request->getVar('identidad'),
                'nombre' => $this->request->getVar('nombre'),
                'telefono' => $this->request->getVar('telefono'),
                'correo' => $this->request->getVar('correo'),
                'direccion' => $this->request->getVar('direccion'),
                'mensaje' => $this->request->getVar('mensaje'),
                'tasa_interes' => $this->request->getVar('tasa_interes'),
                'cuotas' => $this->request->getVar('cuotas')

            ];
            if ($this->admin->update($id, $data) === false) {
                $data['admin'] = $this->admin->first();
                $data['errors'] = $this->admin->errors();
                //dd($data['errors']);
                return view('admin/index', $data);
            }
            if (!empty($img->getName()) && $img->getClientMimeType() === 'image/png') {
    if (!$img->hasMoved()) {
        $ruta_destino = FCPATH . 'assets/img/logo.png'; // Ruta de destino

        if (file_exists($ruta_destino)) {
            unlink($ruta_destino); // Elimina el archivo existente si lo hay
        }

        $img->move(FCPATH . 'assets/img', 'logo.png'); // Mueve la imagen a la nueva ubicación
    }
}


            return redirect()->to(base_url('admin'))->with('respuesta', [
                'type' => 'success',
                'msg' => 'DATOS MODIFICADOS',
            ]);
        }
    }
}

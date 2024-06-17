<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CajasModel;

use function PHPSTORM_META\type;

class CajasController extends BaseController
{
    private $cajas, $session;
    public function __construct()
    {
        helper(['form']);
        $this->cajas = new CajasModel();
        //obtenemos la sesion del usuario
        $this->session = session();
    }
    public function index()
    {
        $data['caja'] = $this->cajas->where([
            'estado' => '1',
            'id_usuario' => $this->session->id_usuario
        ])->first();
        return view('cajas/index',$data);
    }

    public function new()
    {
        return view('cajas/nuevo');
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $data = [
                'id_caja' => $this->request->getVar('id_caja'),
                'monto_inicial' => $this->request->getVar('monto'),
                'fecha_apertura' => date('Y-m-d H:i:s'),
                'id_usuario' =>  $this->session->id_usuario
            ];
            if (empty($consulta)) {
                if ($this->cajas->insert($data) === false) {
                    $data['errors'] = $this->cajas->errors();
                    return view('cajas/nuevo', $data);
                }
                return redirect()->to(base_url('cajas'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'Monto Registrado',
                ]);
            } else {
                return redirect()->to(base_url('cajas'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'Ya Tienes un monto inicial'
                ]);
            }
        }
    }
}

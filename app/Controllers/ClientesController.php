<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientesModel;

class ClientesController extends BaseController
{
    private $clientes;

    public function __construct()
    {
        $this->clientes = new ClientesModel();
        helper(['form']);
    }

    public function index()
    {
        
        return view('clientes/index');
    }

    public function listar()
    {
        $data = $this->clientes->where('estado', 1)->findAll();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function new()
    {
        return view('clientes/nuevo');
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $rutaBaseFotos = WRITEPATH . 'uploads/clientes/';

            $data = [
                'identidad' => $this->request->getVar('identidad'),
                'num_identidad' => $this->request->getVar('num_identidad'),
                'nombre' => $this->request->getVar('nombre'),
                'apellido' => $this->request->getVar('apellido'),
                'telefono' => $this->request->getVar('telefono'),
                'whatsapp' => $this->request->getVar('whatsapp'),
                'correo' => $this->request->getVar('correo'),
                'direccion' => $this->request->getVar('direccion'),
                'nombre_referencia' => $this->request->getVar('nombre_referencia'),
                'direccion_referencia' => $this->request->getVar('direccion_referencia'),
                'telefono_referencia' => $this->request->getVar('telefono_referencia'),
                'foto_identificacion' => null,
                'foto_identificacion_reverso' => null,
                'foto_domicilio' => null,
                'foto_garantia' => null,



            ];

            // Manejo de archivos
            $fotoIdentificacion = $this->request->getFile('foto_identificacion');
            $fotoIdentificacionReverso = $this->request->getFile('foto_identificacion_reverso');
            $fotoDomicilio = $this->request->getFile('foto_domicilio');
            $fotoCliente = $this->request->getFile('foto_cliente');

            if ($fotoIdentificacion->isValid()) {
                $nombreFotoIdentificacion = 'identificacion_' . time() . '.' . $fotoIdentificacion->getExtension();
                if ($fotoIdentificacion->move($rutaBaseFotos, $nombreFotoIdentificacion)) {
                    $data['foto_identificacion'] = $nombreFotoIdentificacion;
                }
            }
            if ($fotoIdentificacionReverso->isValid()) {
                $nombreFotoIdentificacionReverso = 'reverso_' . time() . '.' . $fotoIdentificacionReverso->getExtension();
                if ($fotoIdentificacionReverso->move($rutaBaseFotos, $nombreFotoIdentificacionReverso)) {
                    $data['foto_identificacion_reverso'] = $nombreFotoIdentificacionReverso;
                }
            }
            if ($fotoDomicilio->isValid()) {
                $nombreFotoDomicilio = 'domicilio_' . time() . '.' . $fotoDomicilio->getExtension();
                if ($fotoDomicilio->move($rutaBaseFotos, $nombreFotoDomicilio)) {
                    $data['foto_domicilio'] = $nombreFotoDomicilio;
                }
            }
            if ($fotoCliente->isValid()) {
                $nombreFotoCliente = 'cliente_' . time() . '.' . $fotoCliente->getExtension();
                if ($fotoCliente->move($rutaBaseFotos, $nombreFotoCliente)) {
                    $data['foto_cliente'] = $nombreFotoCliente;
                }
            }

            if ($this->clientes->insert($data)) {
                return redirect()->to(base_url('clientes'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'CLIENTE REGISTRADO',
                ]);
            } else {
                $data['errors'] = $this->clientes->errors();
                return view('clientes/nuevo', $data);
            }
        }
    }


    
    public function edit($idCliente)
    {
        $data['cliente'] = $this->clientes->where('id', $idCliente)->first();
        return view('clientes/edit', $data);
    }
    public function update($idCliente)
    {
        if ($this->request->getMethod() === 'put') {
            $rutaBaseFotos = WRITEPATH . 'uploads/clientes/';

            $data = [
                'identidad' => $this->request->getVar('identidad'),
                'num_identidad' => $this->request->getVar('num_identidad'),
                'nombre' => $this->request->getVar('nombre'),
                'apellido' => $this->request->getVar('apellido'),
                'telefono' => $this->request->getVar('telefono'),
                'whatsapp' => $this->request->getVar('whatsapp'),
                'correo' => $this->request->getVar('correo'),
                'direccion' => $this->request->getVar('direccion'),
                'nombre_referencia' => $this->request->getVar('nombre_referencia'),
                'direccion_referencia' => $this->request->getVar('direccion_referencia'),
                'telefono_referencia' => $this->request->getVar('telefono_referencia'),
            ];

            // Manejo de archivos
            $fotoIdentificacion = $this->request->getFile('foto_identificacion');
            $fotoIdentificacionReverso = $this->request->getFile('foto_identificacion_reverso');
            $fotoDomicilio = $this->request->getFile('foto_domicilio');

            $fotoCliente = $this->request->getFile('foto_cliente');

            // Verifica y actualiza la foto de identificación
            if ($fotoIdentificacion->isValid()) {
                $cliente = $this->clientes->find($idCliente);
                $fotoAnterior = $cliente['foto_identificacion'];

                if (!empty($fotoAnterior)) {
                    unlink($rutaBaseFotos . $fotoAnterior);
                }

                $nombreFotoIdentificacion = 'identificacion_' . time() . '.' . $fotoIdentificacion->getExtension();
                if ($fotoIdentificacion->move($rutaBaseFotos, $nombreFotoIdentificacion)) {
                    $data['foto_identificacion'] = $nombreFotoIdentificacion;
                }
            }
            // Verifica y actualiza la foto de identificacion del reverso
            if ($fotoIdentificacionReverso->isValid()) {
                $cliente = $this->clientes->find($idCliente);
                $fotoAnterior = $cliente['foto_identificacion_reverso'];

                if (!empty($fotoAnterior)) {
                    unlink($rutaBaseFotos . $fotoAnterior);
                }

                $nombreFotoIdentificacionReverso = 'garantia_' . time() . '.' . $fotoIdentificacionReverso->getExtension();
                if ($fotoIdentificacionReverso->move($rutaBaseFotos, $nombreFotoIdentificacionReverso)) {
                    $data['foto_identificacion_reverso'] = $nombreFotoIdentificacionReverso;
                }
            }

            // Verifica y actualiza la foto de domicilio
            if ($fotoDomicilio->isValid()) {
                $cliente = $this->clientes->find($idCliente);
                $fotoAnterior = $cliente['foto_domicilio'];

                if (!empty($fotoAnterior)) {
                    unlink($rutaBaseFotos . $fotoAnterior);
                }

                $nombreFotoDomicilio = 'domicilio_' . time() . '.' . $fotoDomicilio->getExtension();
                if ($fotoDomicilio->move($rutaBaseFotos, $nombreFotoDomicilio)) {
                    $data['foto_domicilio'] = $nombreFotoDomicilio;
                }
            }



            // Verifica y actualiza la foto de cliente
            if ($fotoCliente->isValid()) {
                $cliente = $this->clientes->find($idCliente);
                $fotoAnterior = $cliente['foto_cliente'];

                if (!empty($fotoAnterior)) {
                    unlink($rutaBaseFotos . $fotoAnterior);
                }

                $nombreFotoCliente = 'cliente_' . time() . '.' . $fotoCliente->getExtension();
                if ($fotoCliente->move($rutaBaseFotos, $nombreFotoCliente)) {
                    $data['foto_cliente'] = $nombreFotoCliente;
                }
            }

            // Desactivar validaciones
            $this->clientes->skipValidation();

            if ($this->clientes->update($idCliente, $data)) {
                return redirect()->to(base_url('clientes'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'CLIENTE MODIFICADO',
                ]);
            } else {
                $data['errors'] = $this->clientes->errors();
                $data['cliente'] = $this->clientes->where('id', $idCliente)->first();

                return view('clientes/edit', $data);
            }
        }
    }


    public function delete($idCliente)
    {
        if ($this->request->is('delete')) {


            // Obtén la información del cliente antes de eliminarlo
            $cliente = $this->clientes->find($idCliente);

            if (!$cliente) {
                return redirect()->to(base_url('clientes'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'Cliente no encontrado',
                ]);
            }

            // Ruta de las fotos basada en $rutaBaseFotos
            $rutaBaseFotos = WRITEPATH . 'uploads/clientes/';

            // Verifica y elimina la foto de identificación si existe
            if ($cliente['foto_identificacion']) {
                $rutaFotoIdentificacion = $rutaBaseFotos . $cliente['foto_identificacion'];
                if (file_exists($rutaFotoIdentificacion)) {
                    unlink($rutaFotoIdentificacion);
                }
            }
            // Verifica y elimina la foto de reverso de identificacion si existe
            if ($cliente['foto_identificacion_reverso']) {
                $rutaIdentificacionReverso = $rutaBaseFotos . $cliente['foto_identificacion_reverso'];
                if (file_exists($rutaIdentificacionReverso)) {
                    unlink($rutaIdentificacionReverso);
                }
            }

            // Verifica y elimina la foto de domicilio si existe
            if ($cliente['foto_domicilio']) {
                $rutaFotoDomicilio = $rutaBaseFotos . $cliente['foto_domicilio'];
                if (file_exists($rutaFotoDomicilio)) {
                    unlink($rutaFotoDomicilio);
                }
            }



            // Verifica y elimina la foto de cliente si existe
            if ($cliente['foto_cliente']) {
                $rutaFotoCliente = $rutaBaseFotos . $cliente['foto_cliente'];
                if (file_exists($rutaFotoCliente)) {
                    unlink($rutaFotoCliente);
                }
            }

            // Elimina al cliente
            $data = $this->clientes->delete($idCliente);

            if ($data) {
                return redirect()->to(base_url('clientes'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'CLIENTE DADO DE BAJA junto con sus fotos',
                ]);
            } else {
                return redirect()->to(base_url('clientes'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            }
        }
    }
}

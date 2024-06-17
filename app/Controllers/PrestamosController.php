<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\ClientesModel;
use App\Models\DetPrestamoModel;
use App\Models\PrestamosModel;

// reference the Dompdf namespace
use Dompdf\Dompdf;
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\Exception as PHPMailerException;




class PrestamosController extends BaseController
{
    private $empresa, $clientes, $prestamos, $detalle, $session, $reglas;
    public function __construct()
    {
        helper(['form', 'fecha']);
        //asemos una instancia del modelo admin model para poder acceder a sus datos
        $this->empresa = new AdminModel();
        //asemos una instancia del modelo clientes model para poder acceder a sus datos
        $this->clientes = new ClientesModel();
        //asemos una instancia del modelo prestamos model para poder acceder a sus datos
        $this->prestamos = new PrestamosModel();
        //asemos una instancia del modelo detale model para poder acceder a sus datos
        $this->detalle = new DetPrestamoModel();
        //obtenemos la sesion del usuario
        $this->session = session();
    }
    public function index()
    {
        $data['empresa'] = $this->empresa->first();
        return view('prestamos/nuevo', $data);
    }

    public function buscarCliente()
    {
        if ($this->request->is('get') && !empty($this->request->getVar('term'))) {
            $term = $this->request->getVar('term');

            $data = $this->clientes
                ->groupStart()
                ->like('nombre', $term)
                ->orLike('apellido', $term)
                ->orLike('num_identidad', $term)
                ->orLike('correo', $term)
                ->orLike('telefono', $term)
                ->groupEnd()
                ->where('estado', '1')
                ->findAll(10);

            $result = array();
            foreach ($data as $cliente) {
                $datos['id'] = $cliente['id'];
                $datos['value'] = $cliente['num_identidad'] . ' - ' . $cliente['nombre'] . ' ' . $cliente['apellido'];
                array_push($result, $datos);
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            die();
        }
    }


    private function calcularFechaVencimiento($modalidad, $fechaActual)
    {
        switch ($modalidad) {
            case 'DIARIO':
                return date('Y-m-d', strtotime($fechaActual . '+1 days'));
            case 'SEMANAL':
                return date('Y-m-d', strtotime($fechaActual . '+7 week'));
            case 'QUINCENAL':
                return date('Y-m-d', strtotime($fechaActual . '+15 days'));
            case 'MENSUAL':
                return date('Y-m-d', strtotime($fechaActual . '+1 month'));
            default:
                return date('Y-m-d', strtotime($fechaActual . '+1 year'));
        }
    }

    // funcion optimizada
            public function create()
            {
                if ($this->request->is('post')) {
                    $fecha_venc = null;
                    $fecha = date('Y-m-d');

                    $data = [
                        'cliente' => $this->request->getVar('cliente'),
                        'importe' => $this->request->getVar('importe_credito'),
                        'modalidad' => $this->request->getVar('modalidad'),
                        'tasa_interes' => $this->request->getVar('tasa_interes'),
                        'cuotas' => $this->request->getVar('cuotas'),
                        'fecha' => date('Y-m-d H:i:s'),
                        'fecha_venc' => $this->calcularFechaVencimiento($this->request->getVar('modalidad'), $fecha),
                        'id_cliente' => $this->request->getVar('id_cliente'),
                        'id_usuario' =>  $this->session->id_usuario //1
                    ];
                    try {
                        if ($this->prestamos->insert($data) === false) {
                            throw new Exception('Error al insertar el préstamo');
                        }

                        $prestamo = $this->prestamos->getInsertID();

                        if ($prestamo > 0) {
                            $ganancia = $this->request->getVar('importe_credito') * ($this->request->getVar('tasa_interes') / 100);
                            $importe_cuota = ($this->request->getVar('importe_credito') / $this->request->getVar('cuotas')) + ($ganancia / $this->request->getVar('cuotas'));

                            for ($i = 1; $i <= $this->request->getVar('cuotas'); $i++) {
                                $prestDetalle = $this->detalle->insert([
                                    'cuota' => $i,
                                    'fecha_venc' => $this->calcularFechaVencimiento($this->request->getVar('modalidad'), $fecha_venc),
                                    'importe_cuota' => $importe_cuota,
                                    'id_prestamo' => $prestamo,
                                ]);

                                // Consulta de vencimiento
                                $consulta = $this->detalle->where('id', $prestDetalle)->first();

                                // Calcular vencimiento
                                $fecha_venc = $this->calcularFechaVencimiento($this->request->getVar('modalidad'), $consulta['fecha_venc']);
                            }

                            // Obtener el ID del préstamo recién registrado
                            $idPrestamo = $this->prestamos->getInsertID();

                            if ($idPrestamo > 0) {
                                // Redirigir a la vista detail con el ID del préstamo
                                return redirect()->to(base_url("prestamos/detail/$idPrestamo"))->with('respuesta', [
                                    'type' => 'success',
                                    'msg' => 'PRESTAMO REGISTRADO',
                                ]);
                            } else {
                                return redirect()->to(base_url('prestamos/historial'))->with('respuesta', [
                                    'type' => 'warning',
                                    'msg' => 'ERROR AL REALIZAR PRESTAMO',
                                ]);
                            }
                        } else {
                            return redirect()->to(base_url('prestamos/historial'))->with('respuesta', [
                                'type' => 'warning',
                                'msg' => 'ERROR AL REALIZAR PRESTAMO',
                            ]);
                        }
                    } catch (Exception $e) {
                        $data['errors'] = $e->getMessage();
                        $data['empresa'] = $this->empresa->first();
                        $data['modalidad'] = $this->request->getVar('modalidad');
                    }
                }
            }

    public function detail($id)
    {
        $data['prestamo'] = $this->prestamos
            ->select('prestamos.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.whatsapp, c.correo, u.nombre AS usuario, u.apellido AS user_apellido')
            ->join('clientes AS c', 'prestamos.id_cliente = c.id')
            ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
            ->where('prestamos.id', $id)->first();

        $data['detalles'] = $this->detalle->where('id_prestamo', $id)->findAll();

        return view('prestamos/detail', $data);
    }

    public function reporte($id)
    {
        $data['prestamo'] = $this->prestamos
            ->select('prestamos.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.direccion, c.whatsapp, c.correo, u.nombre AS usuario, u.apellido AS user_apellido')
            ->join('clientes AS c', 'prestamos.id_cliente = c.id')
            ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
            ->where('prestamos.id', $id)->first();

        $data['detalles'] = $this->detalle->where('id_prestamo', $id)->findAll();
        $data['empresa'] =  $this->empresa->first();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        ob_start();
        echo view('prestamos/contrato', $data);
        $html = ob_get_clean();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'vertical');

        // Render the HTML as PDF
        $dompdf->render();

        // Obtener la fecha actual
        $fechaActual = date('Ymd');

        // Generar el nombre de la carpeta y el nombre del archivo PDF
        $carpetaPrestamo = WRITEPATH . 'uploads/contratos/' . $id;
        $nombreArchivo = 'contrato_' . $id . '_' . $fechaActual . '.pdf';

        // Verificar si la carpeta del préstamo existe, si no, crearla
        if (!is_dir($carpetaPrestamo)) {
            mkdir($carpetaPrestamo, 0755, true);
        }

        // Especificar la ruta completa para guardar el PDF
        $rutaGuardado = $carpetaPrestamo . '/' . $nombreArchivo;


        // Guardar el PDF en la ruta especificada
        file_put_contents($rutaGuardado, $dompdf->output());

        // Output the generated PDF to Browser
        $dompdf->stream('Contrato.pdf', ['Attachment' => false]);

        // Crear una nueva instancia de PHPMailer
        $mail = new PHPMailer();

        $empresa = $this->empresa->first();


        // Configurar el servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pedrozaale135@gmail.com';
        $mail->Password = 'nolemblocymoycal';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configurar el remitente y el destinatario
        $mail->setFrom($empresa['correo'], $empresa['nombre']);
        $mail->addAddress($data['prestamo']['correo'], $data['prestamo']['cliente']);

        // Adjuntar el archivo PDF al correo
        $mail->addAttachment($rutaGuardado, 'Contrato.pdf');

        // Ruta completa al logo de la empresa
        $rutaLogo = FCPATH . 'assets/img/logo.png';

        // Agregar la imagen como un recurso incrustado
        $mail->addEmbeddedImage($rutaLogo, 'logo', 'logo.png');

        // Asignar el asunto y el cuerpo del correo
        $mail->CharSet = 'UTF-8';
        $mail->Subject =  'Contrato de préstamo ';

        $mail->Body = 'Hola <span style="font-weight: bold; font-size: 16px; color: blue;">' . $data['prestamo']['cliente'] . ' ' . $data['prestamo']['apellido'] . '</span>,<br><br>';
        $mail->Body .= 'Adjunto encontrará el contrato con los detalles de su préstamo adquirido con: ' . '<span style="font-weight: bold; font-size: 16px; color: blue;">' . $empresa['nombre'] . '</span>,<br><br>';
        $mail->Body .= '<img src="cid:logo">';
        $mail->isHTML(true); // Indicar que el correo es HTML


        // Enviar el correo
        if ($mail->send()) {
            echo 'Correo enviado correctamente';
        } else {
            echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
        }
    }

    public function reportePagado($id)
    {
        $data['prestamo'] = $this->prestamos
            ->select('prestamos.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.direccion, c.whatsapp, c.correo, u.nombre AS usuario, u.apellido AS user_apellido')
            ->join('clientes AS c', 'prestamos.id_cliente = c.id')
            ->join('usuarios AS u', 'prestamos.id_usuario = u.id')
            ->where('prestamos.id', $id)->first();

        $data['detalles'] = $this->detalle->where('id_prestamo', $id)->findAll();
        $data['empresa'] =  $this->empresa->first();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        ob_start();
        echo view('prestamos/contrato', $data);
        $html = ob_get_clean();
        $options = $dompdf->getOptions();
        $options->set('isJavascriptEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'vertical');

        // Render the HTML as PDF
        $dompdf->render();

        // Obtener la fecha actual
        $fechaActual = date('Ymd');

        // Generar el nombre de la carpeta y el nombre del archivo PDF
        $carpetaPrestamo = WRITEPATH . 'uploads/contratos/' . $id;
        $nombreArchivo = 'contrato_' . $id . '_' . $fechaActual . '.pdf';

        // Verificar si la carpeta del préstamo existe, si no, crearla
        if (!is_dir($carpetaPrestamo)) {
            mkdir($carpetaPrestamo, 0755, true);
        }

        // Especificar la ruta completa para guardar el PDF
        $rutaGuardado = $carpetaPrestamo . '/' . $nombreArchivo;


        // Guardar el PDF en la ruta especificada
        file_put_contents($rutaGuardado, $dompdf->output());

        // Output the generated PDF to Browser
        // $dompdf->stream('Contrato.pdf', ['Attachment' => false]);

        // Crear una nueva instancia de PHPMailer
        $mail = new PHPMailer();

        $empresa = $this->empresa->first();


        // Configurar el servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pedrozaale135@gmail.com';
        $mail->Password = 'nolemblocymoycal';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configurar el remitente y el destinatario
        $mail->setFrom($empresa['correo'], $empresa['nombre']);
        $mail->addAddress($data['prestamo']['correo'], $data['prestamo']['cliente']);

        // Adjuntar el archivo PDF al correo
        $mail->addAttachment($rutaGuardado, 'Contrato.pdf');

        // Ruta completa al logo de la empresa
        $rutaLogo = FCPATH . 'assets/img/logo.png';

        // Agregar la imagen como un recurso incrustado
        $mail->addEmbeddedImage($rutaLogo, 'logo', 'logo.png');

        // Asignar el asunto y el cuerpo del correo
        $mail->CharSet = 'UTF-8';
        $mail->Subject =  'Contrato de Prestamo Liquidado ';

        $mail->Body = 'Hola <span style="font-weight: bold; font-size: 16px; color: blue;">' . $data['prestamo']['cliente'] . ' ' . $data['prestamo']['apellido'] . '</span>,<br><br>';
        $mail->Body .= 'Adjunto encontrará el contrato con los detalles de su préstamo pagado con: ' . '<span style="font-weight: bold; font-size: 16px; color: blue;">' . $empresa['nombre'] . '</span>,<br><br>';
        $mail->Body .= '<img src="cid:logo">';
        $mail->isHTML(true); // Indicar que el correo es HTML


        // Enviar el correo
        if ($mail->send()) {
            echo 'Correo enviado correctamente';
        } else {
            echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
        }
    }

    public function update($id)
    {
        if ($this->request->is('put')) {
            $consulta = $this->detalle->where('id', $id)->first();

            // Verificar si $consulta no es nulo
            if ($consulta) {
                $prestamoId = $consulta['id_prestamo']; // Obtenemos el ID del préstamo

                // Actualizar el estado de la cuota
                $this->detalle->update($id, ['estado' => '0']);

                try {
                    // Enviar correos
                    $this->enviarCorreos($prestamoId);

                    // Verificar si todas las cuotas del préstamo están pagadas
                    $todasPagadas = $this->detalle->where('id_prestamo', $prestamoId)
                        ->where('estado !=', '0')
                        ->countAllResults() == 0;
                    // Si todas las cuotas están pagadas, llamar a la función reporte
                    if ($todasPagadas) {
                        $this->reportePagado($prestamoId);
                    }

                    // Función para enviar el reporte
                    function reportePagado($prestamoId)
                    {
                        // Código para enviar el reporte
                    }

                    // Si todas las cuotas están pagadas, actualizar el estado del préstamo
                    if ($todasPagadas) {
                        $this->prestamos->update($prestamoId, ['estado' => '0']);
                    }

                    return redirect()->to(base_url('prestamos/' . $prestamoId . '/detail'))->with('respuesta', [
                        'type' => 'success',
                        'msg' => 'PAGO REALIZADO.',
                    ]);
                } catch (PHPMailerException $e) {
                    return redirect()->to(base_url('prestamos/' . $prestamoId . '/detail'))->with('respuesta', [
                        'type' => 'danger',
                        'msg' => 'ERROR AL REALIZAR PAGO ' . $e->getMessage(),
                    ]);
                }
            } else {
                // Manejar el caso donde no se encuentra ningún registro con el ID proporcionado
                return redirect()->to(base_url('prestamos'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'Registro no encontrado.',
                ]);
            }
        }
    }

    private function enviarCorreos($prestamoId)
    {
        $correo = $this->request->getPost('correo');
        $cuota = $this->request->getPost('cuota');
        $importe_cuota = $this->request->getPost('importe_cuota');

        $mail = new PHPMailer(true);

        $empresa = $this->empresa->first();

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pedrozaale135@gmail.com';
        $mail->Password = 'nolemblocymoycal';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($empresa['correo'], $empresa['nombre']);
        $mail->addAddress($correo);

        $rutaLogo = FCPATH . 'assets/img/logo.png';
        $mail->addEmbeddedImage($rutaLogo, 'logo', 'logo.png');
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Confirmación de Pago al Préstamo N°' . $prestamoId;
        $mail->Body = "Se ha realizado el pago N° <strong style='font-size: 16px; color: blue;'>$cuota</strong> con un importe de <strong style='font-size: 16px; color: blue;'>$$importe_cuota</strong> de su contrato N° <strong style='font-size: 16px; color: blue;'>$prestamoId</strong>.";
        $mail->Body .= '<img src="cid:logo">';
        $mail->Body .= "Agradecemos tu pago";

        $mail->send();
    }

    public function enviarCorreo()
    {
        $this->reglas = [
            'correo' => [
                'rules' => 'required|valid_email',
            ],
            'mensaje' => [
                'rules' => 'required',

            ]

        ];
        if ($this->request->is('post') && $this->validate($this->reglas)) {
            $correo = $this->request->getVar('correo');

            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                $empresa = $this->empresa->first();
                $cliente = $this->clientes->where('correo', $correo)->first();
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER; 
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'pedrozaale135@gmail.com';                     //SMTP username
                $mail->Password   = 'nolemblocymoycal';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom($empresa['correo'], $empresa['nombre']);
                $mail->addAddress($correo, $cliente['nombre']);

                //Attachments
                // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->CharSet    = 'UTF-8';
                $mail->Subject    = 'Mensaje informativo';
                $mail->Body    = $this->request->getVar('mensaje');
                $mail->send();
                return redirect()->to(base_url('prestamos/detail/' . $this->request->getVar('id_prestamo')))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'CORREO ENVIADO .',
                ]);
            } catch (Exception $e) {
                return redirect()->to(base_url('prestamos/detail/' . $this->request->getVar('id_prestamo')))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'ERROR AL ENVIAR CORREO .' . $mail->ErrorInfo,
                ]);
            }
        } else {
            $data['validator'] = $this->validator;
            $this->prestamos->select('p.*, c.identidad, c.num_identidad, c.nombre AS cliente, c.apellido, c.telefono, c.whatsapp, c.correo, u.nombre AS usuario, u.apellido AS user_apellido');
            $this->prestamos->from('prestamos AS p')->join('clientes AS c', 'p.id_cliente = c.id');
            $data['prestamo'] = $this->prestamos->join('usuarios AS u', 'p.id_usuario = u.id')
                ->where('prestamos.id', $this->request->getVar('id_prestamo'))->first();

            $data['detalles'] = $this->detalle->where('id_prestamo', $this->request->getVar('id_prestamo'))->findAll();
            return view('prestamos/detail', $data);
        }
    }

    public function historial()
    {
        return view('prestamos/historial');
    }

    public function listHistorial()
    {
        if ($this->request->is('get')) {
            $data = $this->prestamos->select('p.*, c.identidad, c.num_identidad, c.nombre, c.apellido, u.nombre AS usuario')
                ->from('prestamos AS p', true)
                ->join('clientes AS c', 'p.id_cliente = c.id')
                ->join('usuarios AS u', 'p.id_usuario = u.id')
                ->findAll();

            for ($i = 0; $i < count($data); $i++) {
                // Obtener la próxima cuota pendiente para cada préstamo
                $proximaCuota = $this->detalle
                    ->where('id_prestamo', $data[$i]['id'])
                    ->where('estado', '1') // Filtrar por cuotas pendientes
                    ->orderBy('fecha_venc', 'ASC') // Ordenar por fecha ascendente
                    ->first();

                // Asignar la fecha de vencimiento de la próxima cuota o null si no hay
                $data[$i]['vencimiento'] = $proximaCuota ? fechaPerzo($proximaCuota['fecha_venc']) : null;
            }

            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
    }

    public function getClientes()
    {
        // 1. Obtener datos de los clientes (puedes usar paginación si es necesario)
        $clientes = $this->clientes->findAll();

        // 2. Para cada cliente, obtener sus préstamos
        foreach ($clientes as &$cliente) {
            $cliente['prestamos'] = $this->prestamos->where('id_cliente', $cliente['id'])->findAll();

            // 3. Para cada préstamo, obtener sus cuotas
            foreach ($cliente['prestamos'] as &$prestamo) {
                $prestamo['cuotas'] = $this->detalle->where('id_prestamo', $prestamo['id'])->findAll();
            }
        }

        // 4. Devolver los datos en formato JSON
        return $this->response->setJSON($clientes);
    }

    function delete($id)
    {
        if ($this->request->is('delete')) {
            $data =  $this->prestamos->delete($id);
            if ($data) {
                return redirect()->to(base_url('prestamos/historial'))->with('respuesta', [
                    'type' => 'success',
                    'msg' => 'Prestamo Eliminado',
                ]);
            } else {
                return redirect()->to(base_url('prestamos/historial'))->with('respuesta', [
                    'type' => 'danger',
                    'msg' => 'ERROR AL ELIMINAR',
                ]);
            }
        }
    }
}

<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.





// Ruta para la página de inicio del sitio
$routes->get('/', 'Home::index');

// Ruta para procesar el formulario de inicio de sesión
$routes->post('/login', 'LoginController::validar');

// Ruta para la página principal de administración
$routes->get('/admin', 'AdminController::index');

// Ruta para el panel de control del administrador
$routes->get('/dashboard', 'AdminController::dashboard');

// Ruta para actualizar los datos de un administrador específico
$routes->put('/admin/(:num)', 'AdminController::update/$1');

// Ruta para listar usuarios
$routes->get('/usuarios/list', 'UsuariosController::listar');

$routes->get('/usuarios/logout', 'UsuariosController::logout');
// Rutas RESTful para gestionar recursos de usuarios (listar, crear, ver, actualizar, eliminar)
$routes->resource('usuarios', ['controller' => 'UsuariosController']);

// Ruta para listar clientes
$routes->get('/clientes/list', 'ClientesController::listar');

// Rutas RESTful para gestionar recursos de clientes (listar, crear, ver, actualizar, eliminar)
$routes->resource('/clientes', ['controller' => 'ClientesController']);

// Ruta para procesar la creación de un nuevo cliente
$routes->post('/clientes/create', 'ClientesController::create');

// Ruta para la página de préstamos
$routes->get('/prestamos', 'PrestamosController::index');

// Ruta para la página de Historial de prestamos
$routes->get('/prestamos/historial', 'PrestamosController::historial');

// Ruta para la funcion listar historial en js
$routes->get('/prestamos/listHistorial', 'PrestamosController::listHistorial');



// Ruta para buscar clientes en la página de préstamos
$routes->get('/prestamos/buscarCliente', 'PrestamosController::buscarCliente');


$routes->get('/prestamos/(:num)/detail/', 'PrestamosController::detail/$1');

// Ruta para buscar clientes en la página de préstamos
$routes->get('/prestamos/(:num)/reporte/', 'PrestamosController::reporte/$1');

// Ruta para guardar un nuevo préstamo
$routes->post('/prestamos', 'PrestamosController::create');
//ruta para enviar correo
$routes->post('/prestamos/enviarCorreo', 'PrestamosController::enviarCorreo');
// ruta para enviar correo del pago 

$routes->put('/prestamos/(:num)', 'PrestamosController::update/$1');
// ruta para eliminar prestamo
$routes->delete('/prestamos/(:num)', 'PrestamosController::delete/$1');
// ruta para enviar a la vista despues de generar un prestamo
$routes->get('prestamos/detail/(:num)', 'PrestamosController::detail/$1');


// Ruta para Cajas
$routes->get('/cajas', 'CajasController::index');
//
$routes->get('/cajas/new', 'CajasController::new');
$routes->get('/cajas/(:num)/new', 'CajasController::edit/$1');
//
$routes->post('/cajas', 'CajasController::create');

$routes->get('clientes', 'PrestamosController::getClientes');
$routes->group('api', function ($routes) {
    $routes->get('clientes', 'PrestamosController::getClientes', ['as' => 'api.clientes']);
    // ... otras rutas de tu API
});












/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class GeolocalizacionController extends BaseController
{
    public function index()
    {
        return view('geolocalizacion/index');
    }
}

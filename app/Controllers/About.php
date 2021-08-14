<?php

namespace App\Controllers;

class About extends BaseController
{
    public function index()
    {
        $data['judul'] = 'About';
        echo view('about/index', $data);
    }
}

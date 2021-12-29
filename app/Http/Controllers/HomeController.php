<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function __construct(){

        parent::__construct();
    }

    public function index()
    {
        $this->data = [
            'title' => 'Home',
        ];

        return view('pages.home.index')->with($this->data);
    }
}

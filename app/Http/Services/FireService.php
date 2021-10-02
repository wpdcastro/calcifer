<?php

namespace App\Http\Controllers;

class FireService extends Service
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Fire $fire)
    {
        $this->fire = $fire;
    }

    public function index()
    {
        $this->fire->paginate(10);
    }

    //
}

<?php

namespace App\Http\Controllers;

class FireController extends Controller
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
        return $this->fire->paginate(10);
    }

    public function show($courseId)
    {
        return $this->fire->find($courseId);
    }

    public function sendNotification()
    {
        
    }
}

<?php

namespace App\Services;

class FireSourceService extends Service
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

    public function updateDatabase()
    {
        $database = [ ["rapaz"], ["rapaz2"], ["rapaz4"] ];
        return $database;
    }
}

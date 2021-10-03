<?php

namespace App\Http\Controllers;

use Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
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
        NotificationService.sendNotification();

        return response()->json(['data' => ['message' => 'Mensagem enviada succefully updated']]);
    }
}

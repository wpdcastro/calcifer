<?php

namespace App\Http\Controllers;

class FireController extends Controller
{
    protected $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        return $this->user->paginate(10);
    }

    public function show($userId)
    {
        return $this->user->find($userId);
    }

    public function destroy($userId)
    {
        //$userToDestroy = $this->user->find($userId);
        return $this->user->destroy($userId);
    }
}
<?php

namespace App\Http\Controllers;
use App\Model\User;

class UserController extends Controller
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
        $this->user->destroy($userId);
        return response()->json(['data' => ['message' => 'User removed with success']]);
    }

    public function store(Request $request)
    {
        $this->user->create($request->all());
        return response()->json(['data' => ['message' => 'User succefully created']]);
    }
}
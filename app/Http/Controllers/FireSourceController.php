<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\FireSource;
use Services\FireSourceService;

class FireSourceController extends Controller
{
    private $fire;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FireSource $fire)
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

    public function destroy($courseId)
    {
        return $this->fire->destroy($courseId);
    }

    public function store(Request $request)
    {
        $this->fire->create($request->all());
        return response()->json(['data' => ['message' => 'Fire succefully created']]);
    }

    public function update($fire, Request $request)
    {
        $fire = $this->fire->find($fireId);
        $fire->fire->update($request);

        return response()->json(['data' => ['message' => 'Fire succefully updated']]);
    }

    public function updateDatabase()
    {
        $fireDatabase = FireSourceService.updateDatabase();

        foreach($fireData as $fireDatabase)
        {
            $this->fire->create($fireData->all());
        }

        $tata = new FireSource();
        return $tata;
        //return store($tata);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\ProfessionDataTable;
use App\Repositories\Contracts\ProfessionRepositoryInterface;
use App\Models\Profession;

class ProfessionController extends Controller
{
    public function __construct(protected ProfessionDataTable $professionDataTable, protected ProfessionRepositoryInterface $professionInterface)
    {
        $this->professionInterface = $professionInterface;
        $this->professionDataTable = $professionDataTable;
    }

    public function index(ProfessionDataTable $professionDataTable)
    {
        return $this->professionInterface->index($this->professionDataTable);
    }

    public function create()
    {
        return $this->professionInterface->create();
    }

    public function store(Request $request)
    {
        return $this->professionInterface->store($request);
    }

    public function edit($id)
    {
        return $this->professionInterface->edit($id);
    }

    public function update(Request $request, $id)
    {
        return $this->professionInterface->update($request, $id);
    }

    public function destroy($id) {
        return $this->professionInterface->delete($id);
    }
}
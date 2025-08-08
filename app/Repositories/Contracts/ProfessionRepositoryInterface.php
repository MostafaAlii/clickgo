<?php

namespace App\Repositories\Contracts;

use App\DataTables\Dashboard\Admin\ProfessionDataTable;
use Illuminate\Http\Request;
use App\Models\Profession;

interface ProfessionRepositoryInterface
{
    public function index(ProfessionDataTable $professionDataTable);
    public function create();
    public function store(Request $request);
    public function edit($id);
    public function update(Request $request, $id);
    public function delete(int $id);
}

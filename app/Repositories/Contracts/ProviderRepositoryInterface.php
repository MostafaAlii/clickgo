<?php

namespace App\Repositories\Contracts;

use App\DataTables\Dashboard\Admin\ProviderDataTable;
use Illuminate\Http\Request;
use App\Models\Provider;

interface ProviderRepositoryInterface
{
    public function index(ProviderDataTable $providerDataTable);
    public function create();
    public function store(Request $request);
    /*public function edit($id);
    public function update(Request $request, $id);
    public function delete(int $id);*/
}

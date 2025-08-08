<?php

namespace App\Repositories\Contracts;

use App\DataTables\Dashboard\Admin\ServiceDataTable;
use Illuminate\Http\Request;
use App\Models\Service;

interface ServiceRepositoryInterface {
    public function index(ServiceDataTable $serviceDataTable);
    public function create();
    public function store(Request $request);
    public function findById($id);
    public function updateService($id, array $data);
}

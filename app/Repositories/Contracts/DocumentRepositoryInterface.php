<?php

namespace App\Repositories\Contracts;

use App\DataTables\Dashboard\Admin\DocumentDataTable;
use Illuminate\Http\Request;
use App\Models\Document;

interface DocumentRepositoryInterface
{
    public function index(DocumentDataTable $documentDataTable);
    public function create();
    public function store(Request $request);
    public function edit($id);
    public function update(Request $request, $id);
    public function delete(int $id);
}
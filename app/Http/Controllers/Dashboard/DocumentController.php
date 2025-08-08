<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\DocumentDataTable;
use App\Repositories\Contracts\DocumentRepositoryInterface;
use App\Models\Document;

class DocumentController extends Controller
{
    public function __construct(protected DocumentDataTable $documentDataTable, protected DocumentRepositoryInterface $documentInterface)
    {
        $this->documentInterface = $documentInterface;
        $this->documentDataTable = $documentDataTable;
    }

    public function index(DocumentDataTable $documentDataTable)
    {
        return $this->documentInterface->index($this->documentDataTable);
    }

    public function create()
    {
        return $this->documentInterface->create();
    }

    public function store(Request $request)
    {
        return $this->documentInterface->store($request);
    }

    public function edit($id)
    {
        return $this->documentInterface->edit($id);
    }

    public function update(Request $request, $id)
    {
        return $this->documentInterface->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->documentInterface->delete($id);
    }
}

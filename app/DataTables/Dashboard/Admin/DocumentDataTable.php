<?php

namespace App\DataTables\Dashboard\Admin;

use App\DataTables\Base\BaseDataTable;
use App\Models\Document;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class DocumentDataTable extends BaseDataTable
{
    public function __construct(DataTableRequest $request)
    {
        parent::__construct(new Document());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Document $document) {
                return view('dashboard.Admin.documents.btn.actions', compact('document'));
            })
            ->editColumn('created_at', function (Document $document) {
                return $this->formatBadge($this->formatDate($document->created_at));
            })
            ->editColumn('updated_at', function (Document $document) {
                return $this->formatBadge($this->formatDate($document->updated_at));
            })
            ->editColumn('status', function (Document $document) {
                return $this->formatStatus($document->status);
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'status']);
    }

    public function query(): QueryBuilder
    {
        return Document::latest();
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false,],
            ['name' => 'name', 'data' => 'name', 'title' => trans('dashboard/admin.name'),],
            ['name' => 'status', 'data' => 'status', 'title' => trans('dashboard/general.status'),],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false,],
        ];
    }
}

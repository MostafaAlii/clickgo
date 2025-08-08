<?php

namespace App\DataTables\Dashboard\Admin;

use App\DataTables\Base\BaseDataTable;
use App\Models\Profession;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class ProfessionDataTable extends BaseDataTable {
    public function __construct(DataTableRequest $request)
    {
        parent::__construct(new Profession());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Profession $profession) {
                return view('dashboard.Admin.professions.btn.actions', compact('profession'));
            })
            ->editColumn('created_at', function (Profession $profession) {
                return $this->formatBadge($this->formatDate($profession->created_at));
            })
            ->editColumn('updated_at', function (Profession $profession) {
                return $this->formatBadge($this->formatDate($profession->updated_at));
            })
            ->editColumn('status', function (Profession $profession) {
                return $this->formatStatus($profession->status);
            })
            ->addColumn('documents_count', function (Profession $profession) {
                if ($profession->documents_count > 0) {
                    $btn = '<a href="#" class="badge bg-success text-white"
                    data-bs-toggle="modal"
                    data-bs-target="#documentsModal' . $profession->id . '">
                    ' . $profession->documents_count . '
                </a>';
                    $modal = view('dashboard.Admin.professions.btn.documents_modal', [
                        'profession' => $profession
                    ])->render();
                    return $btn . $modal;
                } else {
                    return '<span class="text-danger">لا يوجد مستندات</span>';
                }
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'status', 'documents_count']);
    }

    public function query(): QueryBuilder {
        return Profession::with(['documents' => function ($q) {
            $q->select('documents.id');
        }])->withCount('documents')->latest();
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false,],
            ['name' => 'name', 'data' => 'name', 'title' => trans('dashboard/admin.name'),],
            ['name' => 'documents_count', 'data' => 'documents_count', 'title' => 'المستندات',],
            ['name' => 'status', 'data' => 'status', 'title' => trans('dashboard/general.status'),],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false,],
        ];
    }
}

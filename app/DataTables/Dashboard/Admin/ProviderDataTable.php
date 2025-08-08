<?php

namespace App\DataTables\Dashboard\Admin;

use App\DataTables\Base\BaseDataTable;
use App\Models\Provider;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class ProviderDataTable extends BaseDataTable {
    public function __construct(DataTableRequest $request) {
        parent::__construct(new Provider());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Provider $provider) {
                return view('dashboard.Admin.providers.btn.actions', compact('provider'));
            })
            ->editColumn('created_at', function (Provider $provider) {
                return $this->formatBadge($this->formatDate($provider->created_at));
            })
            ->editColumn('updated_at', function (Provider $provider) {
                return $this->formatBadge($this->formatDate($provider->updated_at));
            })
            ->addColumn('profession', function (Provider $provider) {
                return $provider->profession->name ?? '-';
            })
            ->editColumn('provider', function (Provider $provider) {
                return '<img src="' . $provider->getMediaUrl('provider', $provider, null, 'media', 'provider') . '" class="img-fluid" alt="' . $provider->name . '" style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 5px;"/>';
            })
            ->editColumn('status', function (Provider $provider) {
                $checked = $provider->status === 'approved' ? 'checked' : '';
                return '
                    <label class="switch">
                        <input type="checkbox" class="status-toggle" data-id="' . $provider->id . '" ' . $checked . '>
                        <span class="slider round"></span>
                    </label>
                ';
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'status', 'profession', 'provider']);
    }

    public function query(): QueryBuilder
    {
        return Provider::with(['profession', 'media'])->latest();
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false,],
            ['name' => 'name', 'data' => 'name', 'title' => trans('dashboard/admin.name'),],
            ['name' => 'provider', 'data' => 'provider', 'title' => 'الصوره', 'orderable' => false, 'searchable' => false],
            ['name' => 'profession', 'data' => 'profession', 'title' => 'المهنة', 'orderable' => false, 'searchable' => false,],
            ['name' => 'status', 'data' => 'status', 'title' => trans('dashboard/general.status'),],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false,],
        ];
    }
}

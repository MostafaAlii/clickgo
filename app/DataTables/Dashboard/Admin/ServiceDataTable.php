<?php
namespace App\DataTables\Dashboard\Admin;
use App\DataTables\Base\BaseDataTable;
use App\Models\Service;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class ServiceDataTable extends BaseDataTable {
    public function __construct(DataTableRequest $request) {
        parent::__construct(new Service());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Service $service) {
                return view('dashboard.Admin.services.btn.actions', compact('service'));
            })
            ->editColumn('created_at', function (Service $service) {
                return $this->formatBadge($this->formatDate($service->created_at));
            })
            ->editColumn('updated_at', function (Service $service) {
                return $this->formatBadge($this->formatDate($service->updated_at));
            })
            ->editColumn('service', function (Service $service) {
                return '<img src="' . $service->getMediaUrl('service', $service, null, 'media', 'service') . '" class="img-fluid" alt="' . $service->name . '" style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 5px;"/>';
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'service']);
    }

    public function query(): QueryBuilder {
        return Service::with(['media'])->latest();
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false,],
            ['name' => 'name', 'data' => 'name', 'title' => trans('dashboard/admin.name'),],
            ['name' => 'service', 'data' => 'service', 'title' => 'الصوره', 'orderable' => false, 'searchable' => false],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false,],
        ];
    }
}

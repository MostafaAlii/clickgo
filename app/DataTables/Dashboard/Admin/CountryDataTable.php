<?php
namespace App\DataTables\Dashboard\Admin;
use App\DataTables\Base\BaseDataTable;
use App\Models\Country;
use Yajra\DataTables\EloquentDataTable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Utilities\Request as DataTableRequest;

class CountryDataTable extends BaseDataTable {
    public function __construct(DataTableRequest $request) {
        parent::__construct(new Country());
        $this->request = $request;
    }

    public function dataTable($query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function (Country $country) {
                return view('dashboard.Admin.country.actions.btns', compact('country'));
            })
            ->editColumn('created_at', function (Country $country) {
                return $this->formatBadge($this->formatDate($country->created_at));
            })
            ->editColumn('updated_at', function (Country $country) {
                return $this->formatBadge($this->formatDate($country->updated_at));
            })
            /*->editColumn('name', function (Country $country) {
                return '<a href="' . route('admin.country.show', $country->uuid) . '">' . $country->name . '</a>';
            })*/
            ->editColumn('country', function (Country $country) {
                return '<img src="' . $country->getMediaUrl('countries', $country, null, 'media', 'country') . '" class="img-fluid" alt="' . $country->name . '" style="max-width: 100px; max-height: 100px; object-fit: cover; border-radius: 5px;"/>';
            })
            ->rawColumns(['action', 'created_at', 'updated_at', 'country']);
    }

    public function query(): QueryBuilder {
        return Country::with(['media'])->latest();
    }

    public function getColumns(): array
    {
        return [
            ['name' => 'id', 'data' => 'id', 'title' => '#', 'orderable' => false, 'searchable' => false,],
            ['name' => 'name', 'data' => 'name', 'title' => trans('dashboard/admin.name'),],
            ['name' => 'country', 'data' => 'country', 'title' => 'الصوره', 'orderable' => false, 'searchable' => false],
            ['name' => 'created_at', 'data' => 'created_at', 'title' => trans('dashboard/general.created_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'updated_at', 'data' => 'updated_at', 'title' => trans('dashboard/general.updated_at'), 'orderable' => false, 'searchable' => false,],
            ['name' => 'action', 'data' => 'action', 'title' => trans('dashboard/general.actions'), 'orderable' => false, 'searchable' => false,],
        ];
    }
}

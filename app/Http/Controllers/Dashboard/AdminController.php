<?php
namespace App\Http\Controllers\Dashboard;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\AdminRepositoryInterface;
use App\DataTables\Dashboard\Admin\AdminDataTable;
use App\Http\Requests\Dashboard\AdminRequestValidation;
class AdminController extends Controller implements AdminRepositoryInterface {

    public function __construct(protected AdminDataTable $adminDataTable, protected AdminRepositoryInterface $adminInterface) {
        $this->adminInterface = $adminInterface;
        $this->adminDataTable = $adminDataTable;
    }

    public function index(AdminDataTable $adminDataTable) {
        return $this->adminInterface->index($this->adminDataTable);
    }

    public function show($uuid) {
        $admin = Admin::whereHas('profile', function($query) use ($uuid) {
            $query->whereUuid($uuid);
        })->firstOrFail();
        //return view('admin.show', compact('admin'));
        return $admin;
    }

    public function store(AdminRequestValidation $request) {
        return $this->adminInterface->store($request);
    }

    public function edit($id) {
        return $this->adminInterface->edit($id);
    }
}

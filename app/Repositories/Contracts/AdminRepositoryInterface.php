<?php
namespace  App\Repositories\Contracts;
use App\DataTables\Dashboard\Admin\AdminDataTable;
use App\Http\Requests\Dashboard\AdminRequestValidation;
interface AdminRepositoryInterface {
    public function index(AdminDataTable $adminDataTable);
    public function store(AdminRequestValidation $request);
    public function edit($id);
    /*public function destroy($request);*/
}

<?php

namespace  App\Repositories\Eloquents;

use App\Models\Admin;
use App\Repositories\Contracts\AdminRepositoryInterface;
use Illuminate\Http\Request;
use App\DataTables\Dashboard\Admin\AdminDataTable;
use Illuminate\Support\Facades\Crypt;
class AdminRepository implements AdminRepositoryInterface
{
    public function index(AdminDataTable $adminDataTable) {
        return $adminDataTable->render('dashboard.Admin.admins.index', ['PageTitle' => trans('dashboard/admin.admins')]);
    }

    public function store($request) {
        try {
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->password = bcrypt($request->password);
            $admin->status = $request->status;
            $admin->type = $request->type;
            $admin->link_password_status = $request->link_password_status;
            if ($request->link_password_status && $request->filled('link_password_protection')) {
                $admin->link_password_protection = bcrypt($request->link_password_protection);
            }
            $admin->save();
            return response()->json(['success' => true, 'message' => trans('dashboard/admin.admin_created_successfully')]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id) {
        $admin = Admin::findOrFail(Crypt::decryptString($id));
        if(! $admin)
            abort(404);
        return $admin;
    }

    public function update($request) {

    }

    public function destroy($request) {}
}

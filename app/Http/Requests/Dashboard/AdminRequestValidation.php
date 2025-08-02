<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequestValidation extends FormRequest {
    public function authorize() {
        return true;
    }

    public function prepareForValidation() {
        $this->merge([
            'status' => $this->filled('status') ? $this->status : 'inactive',
            'link_password_status' => $this->filled('link_password_status') ? $this->link_password_status : 0,
        ]);
    }

    public function rules() {
        $this->prepareForValidation();
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required', 'in:active,inactive'],
            'type' => ['required', 'in:admin,supervisor'],
            'link_password_status' => ['sometimes','boolean'],
        ];
        if ($this->method() == 'PUT' || $this->method() == 'PATCH') {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:admins,email,' . $this->route('admins')];
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }
        return $rules;
    }

    public function messages() {
        return [
            'name.required' => trans('dashboard/admin.name_required'),
            'email.required' => trans('dashboard/admin.email_required'),
            'email.email' => trans('dashboard/admin.email_invalid'),
            'email.unique' => trans('dashboard/admin.email_unique'),
            'phone.required' => trans('dashboard/admin.phone_required'),
            'password.required' => trans('dashboard/admin.password_required'),
            'password.min' => trans('dashboard/admin.password_min'),
            'status.required' => trans('dashboard/admin.status_required'),
            'type.required' => trans('dashboard/admin.type_required'),
            'type.in' => trans('dashboard/admin.type_invalid'),
        ];
    }
}

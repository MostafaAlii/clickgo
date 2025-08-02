<button type="button" class="modal-effect btn btn-sm btn-primary editBtn" style="text-align: center !important" data-toggle="modal" data-id="#editBtn{{Crypt::encryptString($admin->id)}}" data-effect="effect-scale">
    <span class="icon text-white">
        <i class="la la-edit"></i>
        {{ trans('dashboard/general.edit') }}
    </span>
</button>

<button type="button" class="modal-effect btn btn-sm btn-danger deleteBtn" style="text-align: center !important" data-toggle="modal" data-id="#deleteBtn{{Crypt::encryptString($admin->id)}}" data-effect="effect-scale">
    <span class="icon text-white">
        <i class="la la-trash"></i>
        {{ trans('dashboard/general.delete') }}
    </span>
</button>

{{--@include('dashboard.Admin.admins.btn.delete')

@include('dashboard.Admin.admins.btn.edit')--}}

@extends('layouts.back.app')
@section('title', 'Roles')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if (session('success'))
    <div class="text-dark alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <h4 class="mb-1">{{ __($title) }}</h4>

    <p class="mb-6">
        A role provided access to predefined menus and features so that depending on <br />
        assigned role an administrator can have access to what user needs.
    </p>

    <!-- Role cards -->
    <div class="row g-6">
        @foreach($roles as $role)
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-normal text-body">Total {{ $role->users()->count() }} users</h6>
                        <ul class="mb-0 list-unstyled d-flex align-items-center avatar-group">
                            @foreach($role->users()->get() as $user)
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}"
                                class="avatar pull-up">
                                <img class="rounded-circle" src="{{ asset('back/assets/img/avatars/1.png') }}" alt="Avatar" />
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="role-heading">
                            <h5 class="mb-1">{{ Str::ucfirst($role->name) }}</h5>
                            <a href="javascript:;" data-role-id="{{ $role->id }}" data-role-name="{{ $role->name }}" class="role-edit-modal"
                                data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                <span>{{ __('Edit Role') }}</span>
                            </a>
                        </div>

                        @if ($role->name !=  Auth::user()->roles()->first()->name)
                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-delete" data-id="{{ $role->id }}"><i
                            class="ti ti-trash ti-md text-heading"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="row h-100">
                    <div class="col-sm-5">
                        <div class="mt-4 d-flex align-items-end h-100 justify-content-center mt-sm-0">
                            <img src="{{ asset('back/assets/img/illustrations/add-new-roles.png') }}" class="img-fluid mt-sm-4 mt-md-0"
                                alt="add-new-roles" width="83" />
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="text-center card-body text-sm-end ps-sm-0">
                            <button data-bs-target="#addRoleModal" data-bs-toggle="modal" id="addNewRole"
                                class="mb-4 btn btn-sm btn-primary text-nowrap add-new-role">
                                Add New Role
                            </button>

                            <p class="mb-0">
                                Add new role, <br />
                                if it doesn't exist.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Role cards -->

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mb-6 text-center">
                        <h4 class="mb-2 role-title" id="modalRoleTitle">Add New Role</h4>
                        <p>Set role permissions</p>
                    </div>
                    <!-- Add role form -->
                    <form id="addRoleForm" class="row g-6" action="{{ route($route.'store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        <div class="col-12">
                            <label class="form-label" for="modalRoleName">Role Name</label>
                            <input type="text" id="modalRoleName" name="name" class="form-control" placeholder="Enter a role name" tabindex="-1" />
                        </div>
                        <div class="col-12">
                            <h5 class="mb-6">Role Permissions</h5>
                            <!-- Permission table -->
                            <div class="table-responsive">
                                <table class="table table-flush-spacing">
                                    <tbody>
                                        <tr>
                                            <td class="text-nowrap fw-medium text-heading">
                                                Administrator Access
                                                <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Allows a full access to the system"></i>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-end">
                                                    <div class="mb-0 form-check">
                                                        <input class="form-check-input" type="checkbox" id="selectAll" />
                                                        <label class="form-check-label" for="selectAll"> Select All </label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach($acl_menus as $menu)
                                        @php
                                        $menu_permission = [];
                                        if ($menu->permission_option != "") {
                                            $menu_permission = explode(",", $menu->permission_option);
                                        }
                                        @endphp
                                        @if($menu->link != '#')
                                        <tr>
                                            <td class="text-nowrap fw-medium text-heading">{{ Str::ucfirst($menu->name) }}</td>
                                            <td>
                                                <div class="d-flex justify-content-end">
                                                    @foreach($menu_permission as $permission)
                                                    <div class="mb-0 form-check me-4">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="{{ $menu->permission_key . '-' . $permission }}" name="permissions[]"
                                                            value="{{ $menu->permission_key . '-' . $permission  }}" />

                                                        <label class="form-check-label" for="{{ $menu->permission_key }}"> {{
                                                            Str::ucfirst($permission) }} </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Permission table -->
                        </div>
                        <div class="text-center col-12">
                            <button type="submit" class="btn btn-primary me-3">Submit</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                    <!--/ Add role form -->
                </div>
            </div>
        </div>
    </div>
    <!--/ Add Role Modal -->
    <!-- / Add Role Modal -->
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('#selectAll').on('change', function() {
            if ($(this).is(':checked')) {
                $('input[type="checkbox"]').prop('checked', true);
            } else {
                $('input[type="checkbox"]').prop('checked', false);
            }
        });

        $('.role-edit-modal').on('click', function() {
            var roleId = $(this).data('role-id');
            var roleName = $(this).data('role-name');

            let route = "{{ route($route.'edit', ':id') }}";
            route = route.replace(':id', roleId);

            let routeUpdate = "{{ route($route.'update', ':id') }}";
            routeUpdate = routeUpdate.replace(':id', roleId);

            $('.role-title').text('Edit Role - ' + roleName);

            $('#addRoleForm').attr('action', routeUpdate);
            $('input[name="_method"]').val('PATCH');

            $.ajax({
                url: route,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response) {
                            $('#modalRoleName').val(response.role.name);
                            var permissions = response.permissions;
                            permissions.forEach(function(permission) {
                                $('input[name="permissions[]"][value="' + permission + '"]').prop('checked', true);
                            });
                        }
                    },

                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                }
            );
        });

        $('#addNewRole').on('click', function() {
            $('.role-title').text('Add New Role');
            $('#addRoleForm').attr('action', "{{ route($route.'store') }}");
            $('input[name="_method"]').val('POST');
            $('#addRoleForm')[0].reset();
        });

        $('.btn-delete').on('click', function() {
            var id = $(this).data('id');
            let route = "{{ route($route.'destroy', ':id') }}?_method=DELETE";
            route = route.replace(':id', id);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                },
                buttonsStyling: false
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        type: "POST",
                        url: route,
                        success: function(response) {
                            if (response.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then((result) => {
                                    location.reload();
                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    });
                }
            })
        });
    });
</script>
@endpush

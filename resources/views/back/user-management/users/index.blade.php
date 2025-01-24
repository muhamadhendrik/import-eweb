@extends('layouts.back.app')
@section('title', $title)
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-1">{{ __($title) }}</h4>

    {{-- <div class="mb-6 row g-6">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Users</span>
                            <div class="my-1 d-flex align-items-center">
                                <h4 class="mb-0 me-2">{{$totalUser}}</h4>
                                <p class="mb-0 text-success">(100%)</p>
                            </div>
                            <small class="mb-0">Total Users</small>
                        </div>
                        <div class="avatar">
                            <span class="rounded avatar-initial bg-label-primary">
                                <i class="ti ti-user ti-26px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Verified Users</span>
                            <div class="my-1 d-flex align-items-center">
                                <h4 class="mb-0 me-2">{{$verified}}</h4>
                                <p class="mb-0 text-success">(+95%)</p>
                            </div>
                            <small class="mb-0">Recent analytics </small>
                        </div>
                        <div class="avatar">
                            <span class="rounded avatar-initial bg-label-success">
                                <i class="ti ti-user-check ti-26px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Duplicate Users</span>
                            <div class="my-1 d-flex align-items-center">
                                <h4 class="mb-0 me-2">{{$userDuplicates}}</h4>
                                <p class="mb-0 text-success">(0%)</p>
                            </div>
                            <small class="mb-0">Recent analytics</small>
                        </div>
                        <div class="avatar">
                            <span class="rounded avatar-initial bg-label-danger">
                                <i class="ti ti-users ti-26px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Verification Pending</span>
                            <div class="my-1 d-flex align-items-center">
                                <h4 class="mb-0 me-2">{{$notVerified}}</h4>
                                <p class="mb-0 text-danger">(+6%)</p>
                            </div>
                            <small class="mb-0">Recent analytics</small>
                        </div>
                        <div class="avatar">
                            <span class="rounded avatar-initial bg-label-warning">
                                <i class="ti ti-user-search ti-26px"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Users List Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 card-title">User List</h5>
            <div class="dt-buttons d-flex align-items-center">
                <div id="export-buttons" class="ms-2"></div>
                <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-add-record" onclick="window.location.href='{{ route($route . 'create') }}'">
                    <i class="ti ti-plus me-1"></i> Add New Record
                </button>
            </div>
        </div>
        <div class="pt-0 card-datatable table-responsive">
            <table class="table datatables-basic w-100" id="my-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const table = $('#my-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ request()->fullUrl() }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: "name" },
                { data: "email" },
                { data: "role" },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            // dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-6 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end mt-n6 mt-md-0"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            responsive: true,
            scrollY: true,
            scrollX: true, // Aktifkan scroll horizontal
            displayLength: 25,
            lengthMenu: [7, 10, 25, 50, 75, 100],
            language: {
                paginate: {
                    next: '<i class="ti ti-chevron-right ti-sm"></i>',
                    previous: '<i class="ti ti-chevron-left ti-sm"></i>',
                },
            },
            initComplete: function () {
                $(".card-header").after('<hr class="my-0">');
            }
        });

        $('#my-table_wrapper .dataTables_scrollBody').css('min-height', '400px');

        // Create export buttons dynamically
        const exportButtons = new $.fn.dataTable.Buttons(table, {
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-label-primary dropdown-toggle me-4 waves-effect waves-light border-none",
                    text: '<i class="ti ti-file-export ti-xs me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                    buttons: [
                        {
                            extend: "print",
                            text: '<i class="ti ti-printer me-1"></i>Print',
                            className: "dropdown-item",
                            exportOptions: { columns: ':visible' },
                        },
                        {
                            extend: "csv",
                            text: '<i class="ti ti-file-text me-1"></i>Csv',
                            className: "dropdown-item",
                            exportOptions: { columns: ':visible' },
                        },
                        {
                            extend: "excel",
                            text: '<i class="ti ti-file-text me-1"></i>Excel',
                            className: "dropdown-item",
                            exportOptions: { columns: ':visible' },
                        },
                        {
                            extend: "pdf",
                            text: '<i class="ti ti-file-type-pdf me-1"></i>PDF',
                            className: "dropdown-item",
                            exportOptions: { columns: ':visible' },
                        },
                        {
                            extend: "copy",
                            text: '<i class="ti ti-copy me-1"></i>Copy',
                            className: "dropdown-item",
                            exportOptions: { columns: ':visible' },
                        },
                    ],
                },
            ]
        }).container().appendTo('#export-buttons');
    });
</script>
@endpush

{{-- <div class="d-inline-block">
    <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-md"></i>
    </a>
    <ul class="m-0 dropdown-menu dropdown-menu-end">
        <li><a href="javascript:;" class="dropdown-item">Details</a></li>
        <li><a href="javascript:;" class="dropdown-item">Archive</a></li>
        @can($can.'-delete')
        @if (auth()->user()->id != $id)
        <li><a href="javascript:;" onclick="deleteData('{{ route($route.'destroy', $id) }}')"
                class="dropdown-item text-danger delete-record">Delete</a></li>
        @endif
        @endcan
    </ul>
</div> --}}

@can($can.'-update')
<a href="{{ route($route.'edit', $id) }}" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit">
    <i class="ti ti-pencil ti-md"></i>
</a>
@endcan

@can($can.'-delete')
    @if (auth()->user()->id != $id)
    <a onclick="deleteData('{{ route($route.'destroy', $id) }}')" href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit">
        <i class="ti ti-trash ti-md"></i>
    </a>
    @endif
@endcan

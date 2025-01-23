@extends('layouts.back.app')
@section('title', $title)
@push('styles')
<script src="{{ asset('back/assets') }}/vendor/js/helpers.js"></script>
@endpush
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-1">Edit {{ __(Str::singular($title)) }}</h4>

    <div class="col-md">
        <div class="card">
            <h5 class="card-header">Fill the form</h5>
            <div class="card-body">
                <form id="myForm" class="row g-6" method="POST" action="{{ route($route.'update', $user) }}">
                    @method('PATCH')
                    @csrf
                    @include('back.user-management.users._form', ['isEdit' => true])
                    <div class="col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route($route.'index') }}'">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@stop

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush

@extends('layouts.back.app')
@section('title', "Import Eweb")

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="col-md">
        <div class="card">
            <h5 class="card-header">Import POS Eweb (EXCEL)</h5>
            <div class="card-body">
                <form id="myForm" class="row g-6" method="POST" action="{{ route('import-pos-eweb.store') }}" enctype="multipart/form-data">
                    @method('post')
                    @csrf
                    <div class="card-body">
                        <div class="mb-4">
                            <a href="{{ asset('back/assets/excel/template-import-pos.xlsx') }}">Download Template</a>
                        </div>
                        <div class="mb-4">
                            <label for="pos_excel" class="form-label">File Excel</label>
                            <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="form-control @error('pos_excel') is-invalid @enderror" id="pos_excel" name="pos_excel"/>
                            @error('pos_excel')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="progress">
                            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p id="progress-text">0% completed</p>
                    </div>
                    <div class="col-12 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('dashboard') }}'">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@stop

@extends('layouts.app')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Warna</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Warna</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Data
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-lg-10">
                    <div class="form-group">
                        <div class="form-group-feedback form-group-feedback-left">
                            <input name="Keyword" type="text" class="form-control form-control search-keyword" aria-label="Small" onchange="_page._event.search();" placeholder="Search warna">
                            <div class="form-control-feedback form-control-feedback-sm">
                                <i class=" text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 text-right">
                    <div class="form-group">
                        <button type="button" class="btn btn-warning  mr-1" id="btn-search" data-size="small" onclick="_page._event.search();" ><i class=" mr-1"></i>Search</button>
                        <button type="button" class="btn btn-success" data-size="small" data-action="add" onclick="_page._event.form(this);"><i class=" mr-1"></i>Add</button>
                    </div>
                </div>
            </div>

            <div class="content-table">
                @include($TableList)
            </div>
        </div>
    </div>

</div>

<div id="modal-form" class="modal fade" data-keyboard="false" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>

            <form id="ModalFormAction" action="">
                <input type="hidden" class="module" name="module" value="">
                <input type="hidden" class="act" name="act" value="">
                <input type="hidden" class="kode_warna" name="kode_warna" value="">
                <input type="hidden" class="v_warna_old" name="v_warna_old" value="">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-form-label font-weight-semibold">Name<span class="text-danger ml-1">*</span></label>
                        <input type="text" class="form-control form-control v_warna" placeholder="Warna Name" name="v_warna" value="" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi-alarm mr-1"></i>Cancel</button>
                    <button type="submit" class="btn btn-success"><i class=" mr-1"></i>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('plugin')
<script src="{{ asset('js/pages/warna.js')}}"></script>
@endsection

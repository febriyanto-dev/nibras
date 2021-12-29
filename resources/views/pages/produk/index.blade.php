@extends('layouts.app')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Produk</h1>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('home')}}">Dashboard</a></li>
        <li class="breadcrumb-item active">Produk</li>
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
                            <input name="Keyword" type="text" class="form-control form-control search-keyword" aria-label="Small" onchange="_page._event.search();" placeholder="Search produk">
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
            </div>

            <form id="ModalFormAction" action="">
                <input type="hidden" class="module" name="module" value="">
                <input type="hidden" class="act" name="act" value="">
                <input type="hidden" class="kode_barang" name="kode_barang" value="">
                <input type="hidden" class="v_nama_barang_old" name="v_nama_barang_old" value="">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-form-label font-weight-semibold">Name<span class="text-danger ml-1">*</span></label>
                        <input type="text" class="form-control v_nama_barang" placeholder="Barang Name" name="v_nama_barang" value="" required>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="col-form-label font-weight-semibold">Ukuran<span class="text-danger ml-1">*</span></label>
                                <select class="form-control select2-search-modal v_kode_ukuran" name="v_kode_ukuran" style="width: 100%">
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label font-weight-semibold">Warna<span class="text-danger ml-1">*</span></label>
                                <select class="form-control select2-search-modal v_kode_warna" name="v_kode_warna" style="width: 100%">
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="col-form-label font-weight-semibold">Harga Dasar<span class="text-danger ml-1">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">RP</span>
                                    <input type="text" class="form-control FormatKey v_harga_dasar text-end" placeholder="0.00" name="v_harga_dasar" value="" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="col-form-label font-weight-semibold">Harga<span class="text-danger ml-1">*</span></label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">RP</span>
                                    <input type="text" class="form-control FormatKey v_harga text-end" placeholder="0.00" name="v_harga" value="" required>
                                </div>
                            </div>
                        </div>
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
<script src="{{ asset('js/pages/produk.js')}}"></script>
@endsection

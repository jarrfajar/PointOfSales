@extends('layouts.app')

@section('title', 'Blank Page')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href={{ url('library/datatables/datatables.min.css') }}>
    <link rel="stylesheet" href={{ url('library/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ url('library/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}>
    <link rel="stylesheet" href="{{ url('library/select2/dist/css/select2.min.css') }}">

    <style>
        .modal-lg {
            max-width: 90%;
        }
        
        table thead th {
            white-space: nowrap
        }
    </style>
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Barang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Master</a></div>
                    <div class="breadcrumb-item">Barang</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-primary" onclick="showAddModal()">Tambah</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-barang">
                                <thead>
                                    <tr>
                                        <th style="width: 50px">ID</th>
                                        <th>Gudang</th>
                                        <th>Kode barang</th>
                                        <th>Nama barang</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Harga beli</th>
                                        <th>Harga jual</th>
                                        <th>Expired</th>
                                        <th style="width: 10px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        This is card footer
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="modal-barang" data-focus="false" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-barang">
                    <div class="modal-body"> 
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="gudang_id" class="col-sm-3 col-form-label">Gudang</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="gudang_id" name="gudang_id" style="width: 100%;" onchange="removeErrorClass(this)"></select>
                                        <span id="gudang_id_err" class="invalid"  style="color:rgb(220, 53, 69); font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="kode_barang" class="col-sm-3 control-label text-left is-required">Kode Barang</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="kode_barang" name="kode_barang">
                                        <span id="kode_barang_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="nama_barang" class="col-sm-3 control-label text-left is-required">Nama Barang</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nama_barang" name="nama_barang">
                                        <span id="nama_barang_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="tanggal_kadaluarsa" class="col-sm-3 control-label text-left is-required">Tanggal Kadaluarsa</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa">
                                        <span id="tanggal_kadaluarsa_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="kategori_id" class="col-sm-3 col-form-label">Kategori</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="kategori_id" name="kategori_id" style="width: 100%;" onchange="removeErrorClass(this)"></select>
                                        <span id="kategori_id_err" class="invalid"  style="color:rgb(220, 53, 69); font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="satuan_id" class="col-sm-3 col-form-label">Satuan</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="satuan_id" name="satuan_id" style="width: 100%;" onchange="removeErrorClass(this)"></select>
                                        <span id="satuan_id_err" class="invalid"  style="color:rgb(220, 53, 69); font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="harga_beli" class="col-sm-3 control-label text-left is-required">Harga Beli</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="harga_beli" name="harga_beli">
                                        <span id="harga_beli_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="harga_jual" class="col-sm-3 control-label text-left is-required">Harga Jual</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="harga_jual" name="harga_jual">
                                        <span id="harga_jual_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-form-barang">Save</button>
                        <button type="button" class="btn btn-warning" onclick="formReset()">Reset</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="dismis-modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src={{ asset('library/datatables/datatables.min.js') }}></script>
    <script src={{ asset('library/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}></script>
    <script src={{ asset('library/datatables/Select-1.2.4/js/dataTables.select.min.js') }}></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Page Specific JS File -->
    <script src="{{ asset('js/service/service.js') }}"></script>
    <script src="{{ asset('js/page/master/barang.js') }}"></script>
    
@endpush

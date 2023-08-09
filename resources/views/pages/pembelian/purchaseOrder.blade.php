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
    </style>
@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Purchase order</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Pemebelian</a></div>
                    <div class="breadcrumb-item">Purchase order</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <h4></h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-primary" onclick="showModal()">Tambah</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="table-purchase-order">
                            <thead >
                                <tr>
                                    <th style="width: 50px">ID</th>
                                    <th>Nomor PO</th>
                                    <th>Tanggal</th>
                                    <th style="width: 15%">Gudang</th>
                                    <th style="width: 15%">Supplier</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 20%">Total Harga</th>
                                    <th>Status</th>
                                    <th style="width: 100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-whitesmoke">
                        This is card footer
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="modal-purchase-order" data-focus="false" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-purchase-order">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="nomor_purchase_order" class="col-sm-3 control-label text-left is-required">Nomor Purchase Order</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nomor_purchase_order" name="nomor_purchase_order" placeholder="AUTO" readonly>
                                        <span id="nomor_purchase_order_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-group row">
                                    <label for="supplier_id" class="col-sm-2 col-form-label">Supplier</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="supplier_id" name="supplier_id" style="width: 100%;" onchange="removeErrorClass(this)"></select>
                                        <span id="supplier_id_err" class="invalid"  style="color:rgb(220, 53, 69); font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="tanggal" class="col-sm-3 control-label text-left is-required">Tanggal Purchase Order</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="tanggal" name="tanggal" readonly>
                                        <span id="tanggal_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-group row">
                                    <label for="gudang_id" class="col-sm-2 col-form-label">Gudang</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="gudang_id" name="gudang_id" style="width: 100%;" onchange="changeGudang()"></select>
                                        <span id="gudang_id_err" class="invalid"  style="color:rgb(220, 53, 69); font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="total_harga" class="col-sm-3 control-label text-left is-required">Total Harga</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="total_harga" name="total_harga" readonly>
                                        <span id="total_harga_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-group row">
                                    <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6>Barang</h6>
                        <table class="table table-striped" id="table-barang">
                            <thead>
                                <th>Aksi</th>
                                <th style="width: 20%">Nama Barang</th>
                                <th style="width: 10%">Jumlah</th>
                                <th style="width: 20%">Satuan</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        
                        <button type="button" class="btn btn-primary" onclick="addRow()"><i class="fa fa-plus"></i></button> 
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-form-purchase-order">Save</button>
                        <button type="button" class="btn btn-warning" onclick="resetForm()">Reset</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="dismis-modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js" integrity="sha512-42PE0rd+wZ2hNXftlM78BSehIGzezNeQuzihiBCvUEB3CVxHvsShF86wBWwQORNxNINlBPuq7rG4WWhNiTVHFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src={{ asset('library/datatables/datatables.min.js') }}></script>
    <script src={{ asset('library/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}></script>
    <script src={{ asset('library/datatables/Select-1.2.4/js/dataTables.select.min.js') }}></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/pembelian/purchaseOrder.js') }}"></script>
    
@endpush

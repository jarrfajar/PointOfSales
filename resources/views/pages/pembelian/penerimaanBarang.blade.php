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
                <h1>BAPB/Penerimaan Barang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Pembelian</a></div>
                    <div class="breadcrumb-item">BAPB</div>
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
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-penerimaan-barang">
                                <thead >
                                    <tr>
                                        <th style="width: 50px">ID</th>
                                        <th>Nomor BAPB</th>
                                        <th style="width: 15%">Gudang</th>
                                        <th>Supplier</th>
                                        <th>Terima</th>
                                        <th>Jatuh Tempo</th>
                                        <th style="width: 15%">Total harga</th>
                                        <th style="width: 10%">Aksi</th>
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

    <div id="modal-penerimaan-barang" data-focus="false" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-penerimaan-barang">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="nomor_bapb" class="col-sm-3 control-label text-left is-required">Nomor BAPB</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nomor_bapb" name="nomor_bapb" placeholder="AUTO" readonly>
                                        <span id="nomor_bapb_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="nomor_faktur" class="col-sm-3 control-label text-left is-required">Nomor Faktur</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nomor_faktur" name="nomor_faktur" placeholder="AUTO" readonly>
                                        <span id="nomor_faktur_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="supplier_id" class="col-sm-3 col-form-label">Supplier</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="supplier_id" name="supplier_id" style="width: 100%;" onchange="getPurchaseOrder(this.value)"></select>
                                        <span id="supplier_id_err" class="invalid"  style="color:rgb(220, 53, 69); font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="purchase_order_id" class="col-sm-3 col-form-label">Nomor PO</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="purchase_order_id" name="purchase_order_id" style="width: 100%;"></select>
                                        <span id="purchase_order_id_err" class="invalid"  style="color:rgb(220, 53, 69); font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="gudang_id" class="col-sm-3 col-form-label">Gudang</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="gudang_id" name="gudang_id" style="width: 100%;" readonly></select>
                                        <span id="gudang_id_err" class="invalid"  style="color:rgb(220, 53, 69); font-size: 12px"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="tanggal_po" class="col-sm-3 control-label text-left is-required">Tanggal PO</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="tanggal_po" name="tanggal_po" readonly>
                                        <span id="tanggal_po_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="tanggal_terima" class="col-sm-3 control-label text-left is-required">Tanggal Terima</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="tanggal_terima" name="tanggal_terima">
                                        <span id="tanggal_terima_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="tanggal_tempo" class="col-sm-3 control-label text-left is-required">Tanggal Jatuh Tempo</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" id="tanggal_tempo" name="tanggal_tempo">
                                        <span id="tanggal_tempo_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-group row">
                                    <label for="nomor_resi" class="col-sm-3 control-label text-left is-required">Nomor Resi</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="nomor_resi" name="nomor_resi">
                                        <span id="nomor_resi_err" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6>Barang</h6>
                        <table class="table table-striped" id="table-barang">
                            <thead>
                                <th>Aksi</th>
                                <th style="width: 15%">Nama Barang</th>
                                <th style="width: 10%">Jumlah</th>
                                <th style="width: 15%">Satuan</th>
                                <th style="width: 15%">Harga</th>
                                <th style="width: 10%">Diskon(%)</th>
                                <th style="width: 15%">Diskon(Rp)</th>
                                <th style="width: 2%">Pajak</th>
                                <th style="width: 15%">Total Harga</th>
                            </thead>
                            <tbody></tbody>
                        </table>
                        
                        <button type="button" class="btn btn-primary" onclick="addRow()"><i class="fa fa-plus"></i></button> 

                        <div class="row mt-4 px-3" style="gap: 20px">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="sub_total">Sub Total</label>
                                    <input type="text" class="form-control" id="sub_total" name="sub_total" readonly>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="diskon">Diskon</label>
                                    <input type="text" class="form-control" id="diskon" name="diskon" readonly>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="ppn">PPN</label>
                                    <input type="text" class="form-control" id="ppn" name="ppn" readonly>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group row">
                                    <label for="total_harga">Total Harga</label>
                                    <input type="text" class="form-control" id="total_harga" name="total_harga" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-form-penerimaan-barang">Save</button>
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
    <script src="{{ asset('js/page/pembelian/penerimaan_barang.js') }}"></script>
    
@endpush

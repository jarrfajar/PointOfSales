@extends('layouts.app')

@section('title', 'Blank Page')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href={{ url('library/datatables/datatables.min.css') }}>
    <link rel="stylesheet" href={{ url('library/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}>
    <link rel="stylesheet" href={{ url('library/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}>
    <link rel="stylesheet" href="{{ url('library/select2/dist/css/select2.min.css') }}">

@endpush

@section('main')<div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <select class="form-control" id="barang" style="width: 100%;" ></select>
                            <div class="mt-3">
                                <select class="form-control" id="member" style="width: 100%;" ></select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="d-flex justify-content-between">
                                <h1>Total Harga</h1>
                                <input type="hidden" id="total_harga" value="0">
                                <h1 id="total_harga_text">0</h1>
                            </div>
                            <div class="mt-3 d-flex justify-content-between">
                                <label class="custom-switch p-0">
                                <input type="hidden" id="jumlah_points">
                                <input type="hidden" id="points">
                                    <input type="checkbox" id="checkbox-point" name="custom-switch-checkbox" class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description"><span id="jumlah-point" class="fw-bold mr-2"></span>Gunakan Point</span>
                                </label>
                                <input type="text" id="nama-member" class="form-control col-md-6 text-right" value="-" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <form id="form-kasir">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-stripped" id="table-barang">
                                    <caption>List Barang</caption>
                                    <thead>
                                        <th>Aksi</th>
                                        <th>Nama Barang</th>
                                        <th style="width: 10%">Qty</th>
                                        <th style="width: 10%">Satuan</th>
                                        <th>Harga</th>
                                        <th style="width: 10%">Dikson(%)</th>
                                        <th>Sub Total</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-primary mr-2" id="btn-form-barang" onclick="bayar()">Bayar</button>
                            <button type="button" class="btn btn-warning" id="reset-form-modal" onclick="formReset()">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src={{ asset('library/datatables/datatables.min.js') }}></script>
    <script src={{ asset('library/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}></script>
    <script src={{ asset('library/datatables/Select-1.2.4/js/dataTables.select.min.js') }}></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/axios/dist/axios.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/service/service.js') }}"></script>
    <script src="{{ asset('js/page/kasir.js') }}"></script>
    
@endpush

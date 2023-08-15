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
                <h1>Verifikasi BAPB/Penerimaan Barang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Master</a></div>
                    <div class="breadcrumb-item">Verifikasi BAPB</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="table-verifikasi-bapb">
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
                    <div class="card-footer bg-whitesmoke">
                        This is card footer
                    </div>
                </div>
            </div>
        </section>
    </div>

    <x-modal name="verifikasi-bapb">
        <div class="row">
            <x-input-col type="text" name="supplier_id" label="Supplier" readonly></x-input-col>
            <x-input-col type="text" name="nomor_bapb" label="Nomor BAPB" readonly></x-input-col>
        </div>

        <div class="row">
            <x-input-col type="text" name="gudang_id" label="Gudang" readonly></x-input-col>
            <x-input-col type="text" name="nomor_faktur" label="Nomor Faktur" readonly></x-input-col>
        </div>

        <div>
            <button type="button" class="btn btn-primary mb-3 mr-2" onclick="validAll()">Valid Semua</button>
            <button type="button" class="btn btn-danger mb-3" onclick="returAll()">Retur Semua</button>
        </div>
        <div class="table-responsive">
            <table class="table table-striped" id="table-barang">
                <thead>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Diskon(%)</th>
                    <th>Diskon(Rp)</th>
                    <th>Pajak</th>
                    <th>Total Harga</th>
                    <th class="text-center">Validasi</th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </x-modal>
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
    <script src="{{ asset('js/page/pembelian/verifikasiPenerimaanBarang.js') }}"></script>
    
@endpush

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
                                        <th style="width: 10%">Status</th>
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

    <x-modal name="penerimaan-barang">
        <div class="row">
            <x-input-col type="text" name="nomor_bapb" label="Nomor BAPB"  placeholder="AUTO" readonly></x-input-col>
            <x-input-col type="text" name="nomor_faktur" label="Nomor Faktur"  placeholder="AUTO" readonly></x-input-col>
        </div>
        
        <div class="row">
            <x-input-select-col name="supplier_id" label="Supplier" style="width: 100%;" onchange="getPurchaseOrder(this.value)"></x-input-select-col>
            <x-input-select-col name="purchase_order_id" onchange="changePurchaseOrder(this.value)" label="Nomor PO" style="width: 100%;"></x-input-select-col>
        </div>

        <div class="row">
            <x-input-select-col name="gudang_id" label="Gudang" style="width: 100%;" onchange="getPurchaseOrder(this.value)"></x-input-select-col>
            <x-input-col type="text" name="tanggal_po" label="Tanggal PO" readonly></x-input-col>
        </div>

        <div class="row">
            <x-text-area-col name="deskripsi" label="Deskripsi"></x-text-area-col>
            <x-input-col type="text" name="nomor_resi" label="Nomor Resi"></x-input-col>
        </div>

        <div class="row">
            <x-input-col type="date" name="tanggal_terima" label="Tanggal Terima"></x-input-col>
            <x-input-col type="date" name="tanggal_tempo" label="Tanggal Jatuh Tempo"></x-input-col>
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
        
        <button type="button" class="btn btn-primary btn-addRow" onclick="addRow()"><i class="fa fa-plus"></i></button> 

        <div class="row mt-4 px-3" style="gap: 20px">
            <x-input-row type="text" name="sub_total" label="Sub Total" readonly></x-input-row>
            <x-input-row type="text" name="diskon" label="Diskon" readonly></x-input-row>
            <x-input-row type="text" name="ppn" label="PPN" readonly></x-input-row>
            <x-input-row type="text" name="total_harga" label="Total Harga" readonly></x-input-row>
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
    <script src="{{ asset('js/page/pembelian/penerimaan_barang.js') }}"></script>
    
@endpush

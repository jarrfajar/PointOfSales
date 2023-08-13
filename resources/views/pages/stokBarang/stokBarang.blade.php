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
                <h1>Stok Barang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Stok Barang</a></div>
                    <div class="breadcrumb-item">Stok Barang</div>
                </div>
            </div>

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-stok-barang">
                                <thead >
                                    <tr>
                                        <th style="width: 5%">ID</th>
                                        <th>Gudang</th>
                                        <th>Barang</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Minimal</th>
                                        <th>Maximal</th>
                                        <th>Jumlah</th>
                                        <th>Kadaluarsa</th>
                                        <th>Status</th>
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

    <x-modal name="stok-barang">
        <div class="row">
            <x-input-select-col name="gudang_id" label="Gudang" style="width: 100%;" onchange="getPurchaseOrder(this.value)"></x-input-select-col>
            <x-input-col type="text" name="tanggal_po" label="Tanggal PO" readonly></x-input-col>
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Page Specific JS File -->
    <script src="{{ asset('js/service/service.js') }}"></script>
    <script src="{{ asset('js/page/stokBarang/stokBarang.js') }}"></script>
    
@endpush

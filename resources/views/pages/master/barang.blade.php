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

    <x-modal name="barang">
        <div class="row">
            <x-input-select-col name="gudang_id" label="Gudang" onchange="removeErrorClass(this)"></x-input-select-col>
            <x-input-col type="text" name="kode_barang" label="Kode Barang"></x-input-col>
        </div>

        <div class="row">
            <x-input-col type="text" name="nama_barang" label="Nama Barang" onchange="removeErrorClass(this)"></x-input-col>
            <x-input-col type="date" name="tanggal_kadaluarsa" label="Tanggal Kadaluarsa"></x-input-col>
        </div>

        <div class="row">
            <x-input-select-col name="kategori_id" label="Kategori" onchange="removeErrorClass(this)"></x-input-select-col>
            <x-input-select-col name="satuan_id" label="Satuan" onchange="removeErrorClass(this)"></x-input-select-col>
        </div>

        <div class="row">
            <x-input-col type="text" name="harga_beli" label="Harga Beli"></x-input-col>
            <x-input-col type="text" name="harga_jual" label="Harga Jual"></x-input-col>
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
    <script src="{{ asset('js/page/master/barang.js') }}"></script>
    
@endpush

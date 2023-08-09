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
                <h1>Cabang</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Master</a></div>
                    <div class="breadcrumb-item">Cabang</div>
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
                        <table class="table table-striped" id="table-cabang">
                            <thead >
                                <tr>
                                    <th style="width: 50px">ID</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Kepala Cabang</th>
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

    <div id="modal-cabang" data-focus="false" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-cabang">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="nama" class="col-sm-3 control-label text-left is-required">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="nama" name="nama" onchange="removeErrorClass(this)">
                                <span id="nama_err" class="invalid-feedback"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="alamat" class="col-sm-3 control-label text-left is-required is-invalid">Alamat</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="alamat" name="alamat" onchange="removeErrorClass(this)">
                                <span id="alamat_err" class="invalid-feedback"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="kepala_cabang" class="col-sm-3 control-label text-left is-required">Kepala cabang</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="kepala_cabang" name="kepala_cabang" onchange="removeErrorClass(this)">
                                <span id="kepala_cabang_err" class="invalid-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btn-form-cabang">Save</button>
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
    <script src={{ asset('library/datatables/datatables.min.js') }}></script>
    <script src={{ asset('library/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}></script>
    <script src={{ asset('library/datatables/Select-1.2.4/js/dataTables.select.min.js') }}></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/master/cabang.js') }}"></script>
    
@endpush

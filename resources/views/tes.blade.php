@extends('html.html')

@push('js')
<script>
    $(document).ready(function () {
        $('.table').DataTable({
            info: true,
            dom: '<"row"<"col-sm-6 d-flex justify-content-center justify-content-sm-start mb-2 mb-sm-0"l><"col-sm-6 d-flex justify-content-center justify-content-sm-end"f>>rt<"row"<"col-sm-6 mt-0"i><"col-sm-6 mt-2"p>>',
        });
    });
</script>
@endpush

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Server Printer</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Favicons -->
        <link href="assets/img/favicon.ico" rel="icon">
        <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="antialiased">
        <div class="container">
            <div class="row d-flex justify-content-center align-items-center">
                <div class="text-center">
                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'karyawan')
                        <h3>Selamat Datang, {{ $user->role }}</h3>
                    @else
                        <h3>Selamat Datang, {{ $user->nama }}</h3>
                    @endif

                        @if (Auth::user()->role == 'pelanggan')
                            <div class="col-12" id="kelola-penyedia">
                                <div class="card recent-sales overflow-auto">
                                    <div class="card-body">
                                        <h5 class="card-title">Kelola File, {{ $user->saldo }}</h5>
                                        <div class="d-flex justify-content-end mb-2">
                                            <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#TambahModal">
                                                <i class="bi bi-plus-circle-fill"></i> Upload File
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-end mb-2">
                                            <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#convertModal">
                                                <i class='bx bx-upload'></i> Convert Your File
                                            </button>
                                        </div>
                                        <div class="d-flex justify-content-end mb-2">
                                            <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#TambahSaldo">
                                                <i class='bx bx-upload'></i> Masukkan Token Tambah Saldo
                                            </button>
                                        </div>
                                        <table class="table table-striped table-hover border table-bordered align-middle">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No.</th>
                                                    <th scope="col">Nama File</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($file as $index => $data )
                                                    <tr>
                                                        <th>{{ $index+1 }}</th>
                                                        <td>{{ $data->name }}</td>
                                                        <td>
                                                            <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#showModal{{ $index+1 }}">
                                                                    <i class="bi bi-eye"></i> Lihat
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty

                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- uppload file Modal -->
                            <div class="modal fade" id="TambahModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Upload File</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('uploadFile',['id_user' => $user->id ]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf @method('put')
                                                <div class="container-fluid">
                                                    <div class="row gy-2">
                                                        <div class="col-md-12">
                                                            <label for="">Nama</label>
                                                            <input name="nama" type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                                                            @error('nama')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="" class="mb-2 btn">Pilih Tipe File</label>
                                                            <select name="tipe_file" class="form-select @error('tipe_file') is-invalid @enderror" required>
                                                                <option value="hitam_putih">Hitam Putih</option>
                                                                <option value="berwarna">Berwarna</option>
                                                            </select>
                                                            @error('tipe_file')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-12">
                                                            <label for="">File PDF</label>
                                                            <input name="file" type="file" class="form-control @error('file') is-invalid @enderror">
                                                            @error('file')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-main">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reedem token Modal -->
                            <div class="modal fade" id="TambahSaldo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Redeem Token</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                        <form action="{{ route('redeem.token') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="token">Masukkan Token</label>
                                                <input type="text" name="token" id="token" class="form-control" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Redeem Token</button>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- show file Modal -->
                            @foreach ( $file as $index => $data )
                            <div class="modal fade" id="showModal{{ $index+1 }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Show File</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h1 class="modal-title fs-5">{{ $data->name }}</h1>
                                            <iframe height="700" width="1100" src="{{ asset('storage/'.$data->file_path) }}"></iframe>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- convert file Modal -->
                            <div class="modal fade" id="convertModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Convert File</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('convertToPdfApi') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="container-fluid">
                                                    <div class="row gy-2">
                                                        <div class="col-12">
                                                            <label for="">File PDF</label>
                                                            <input name="file_word" type="file" class="form-control @error('file') is-invalid @enderror">
                                                            @error('file')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-main">Convert</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @elseif (Auth::user()->role == 'admin')
                            <div class="col-12" id="kelola-penyedia">
                                <div class="card recent-sales overflow-auto">
                                    <div class="card-body">
                                        <h5 class="card-title">Kelola Data</h5>

                                        <!-- Pilihan Kelola -->
                                        <div class="d-flex justify-content-start mb-3">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="kelolaOptions" id="kelolaUserOption" value="kelolaUser" checked>
                                                <label class="form-check-label" for="kelolaUserOption">Kelola User</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="kelolaOptions" id="kelolaHargaOption" value="kelolaHarga">
                                                <label class="form-check-label" for="kelolaHargaOption">Kelola Harga Cetak</label>
                                            </div>
                                        </div>

                                        <!-- Tabel Kelola User -->
                                        <div id="kelolaUserTable">
                                            <div class="d-flex justify-content-end mb-2">
                                                <button class="btn btn-main" data-bs-toggle="modal" data-bs-target="#TambahModal">
                                                    <i class="bi bi-plus-circle-fill"></i> Tambah User
                                                </button>
                                            </div>
                                            <table class="table table-striped table-hover border table-bordered align-middle">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">No.</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Role</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ( $pengguna as $index => $data )
                                                        <tr>
                                                            <th>{{ $index+1 }}</th>
                                                            <td>{{ $data->email }}</td>
                                                            <td>{{ $data->role }}</td>
                                                            <td>{{ $data->status }}</td>
                                                            <td>
                                                                <div class="flex flex-row gap-1 justify-content-center">
                                                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAksi{{ $index+1 }}">
                                                                        <i class="bi bi-pen"></i> Aksi
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty

                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Tabel Kelola Harga Cetak -->
                                        <div id="kelolaHargaTable" style="display: none;">
                                            <table class="table table-striped table-hover border table-bordered align-middle">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Tipe Dokumen</th>
                                                        <th scope="col">Harga</th>
                                                        <th scope="col">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ( $harga_cetak as $index => $data )
                                                        <tr>
                                                            <td>{{ $data->tipe_dokumen }}</td>
                                                            <td>{{ $data->harga }}</td>
                                                            <td>
                                                                <div class="flex flex-row gap-1 justify-content-center">
                                                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalAksiHarga{{ $index+1 }}">
                                                                        <i class="bi bi-pen"></i> Aksi
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty

                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tambah Modal -->
                            <div class="modal fade" id="TambahModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Tambah User</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('tambahAkunUser') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="container-fluid">
                                                    <div class="row gy-2">
                                                        <div class="col-md-6">
                                                            <label for="">Email</label>
                                                            <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                                            @error('email')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="">Password</label>
                                                            <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}" required>
                                                            @error('password')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="">Nama</label>
                                                            <input name="nama" type="text" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                                                            @error('nama')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="">Role</label>
                                                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                                                <option value="pelanggan">Mahasiswa</option>
                                                                <option value="karyawan">Karyawan</option>
                                                            </select>
                                                            @error('Role')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="">RFID</label>
                                                            <input name="RFID" type="text" class="form-control @error('RFID') is-invalid @enderror" value="{{ old('RFID') }}" required>
                                                            @error('RFID')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-main">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Aksi Modal -->
                            @foreach ( $pengguna as $index => $data )
                            <div class="modal fade" id="modalAksi{{ $index+1 }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Pilih Aksi Yang Ingin Dilakukan.</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="flex flex-column gap-1 justify-content-center">
                                                <form method="post" action="{{ route('resetPassword') }}">
                                                    @csrf 
                                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="bi bi-lock"></i> Reset Password
                                                    </button>
                                                </form>
                                                <form action="{{ route('editStatusPenyedia',['id_user' => $data->id ]) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf @method('put')
                                                    <div class="container-fluid">
                                                        <div class="row gy-2">
                                                            <div class="col-12">
                                                                <label for="" class="mb-2 btn">Ubah Status</label>
                                                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                                                    <option value="aktif" {{ $data->status == 'aktif' ? 'selected' : '' }}>aktif</option>
                                                                    <option value="belum aktif" {{ $data->status == 'belum aktif' ? 'selected' : '' }}>belum aktif</option>
                                                                </select>
                                                                @error('status')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            <!-- Aksi Modal -->
                            @foreach ( $harga_cetak as $index => $data )
                            <div class="modal fade" id="modalAksiHarga{{ $index+1 }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Edit Harga Cetak</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{ route('updateHargaCetak', ['id' => $data->id]) }}">
                                                @csrf 
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="tipe_dokumen{{ $index+1 }}" class="form-label">Tipe Dokumen</label>
                                                    <input type="text" class="form-control" id="tipe_dokumen{{ $index+1 }}" name="tipe_dokumen" value="{{ $data->tipe_dokumen }}" readonly>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="harga{{ $index+1 }}" class="form-label">Harga</label>
                                                    <input type="number" class="form-control" id="harga{{ $index+1 }}" name="harga" value="{{ $data->harga }}" required>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        @elseif (Auth::user()->role == 'karyawan')
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Pilih Tabel</h5>
                                                <div class="d-flex">
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio" name="tableOption" id="saldoTableOption" value="saldoTable" checked>
                                                        <label class="form-check-label" for="saldoTableOption">
                                                            Tabel Saldo
                                                        </label>
                                                    </div>
                                                    <div class="form-check me-3">
                                                        <input class="form-check-input" type="radio" name="tableOption" id="transaksiMahasiswaTableOption" value="transaksiMahasiswaTable">
                                                        <label class="form-check-label" for="transaksiMahasiswaTableOption">
                                                            Tabel Transaksi Mahasiswa
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="tableOption" id="transaksiSaldoTableOption" value="transaksiSaldoTable">
                                                        <label class="form-check-label" for="transaksiSaldoTableOption">
                                                            Tabel riwayat pembuatan token saldo
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <!-- tabel saldo -->
                                    <div class="col-12 table-container" id="saldoTable">
                                        <div class="card recent-sales overflow-auto">
                                            <div class="card-body">
                                                <h5 class="card-title">Saldo Mahasiswa</h5>
                                                <table class="table table-striped table-hover border table-bordered align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">No.</th>
                                                            <th scope="col">Nama</th>
                                                            <th scope="col">Saldo</th>
                                                            <th scope="col">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($mahasiswa as $index => $data )
                                                            <tr>
                                                                <th>{{ $index+1 }}</th>
                                                                <td>{{ $data->nama }}</td>
                                                                <td>{{ $data->saldo }}</td>
                                                                <td>
                                                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#tambahSaldoModal{{ $index+1 }}">
                                                                            <i class="bi bi-plus-circle-fill"></i> Tambah saldo
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tabel transaksi print -->
                                    <div class="col-12 table-container" id="transaksiMahasiswaTable" style="display: none;">
                                        <div class="card recent-sales overflow-auto">
                                            <div class="card-body">
                                                <h5 class="card-title">Tabel transaksi mahasiswa</h5>
                                                <table class="table table-striped table-hover border table-bordered align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">No.</th>
                                                            <th scope="col">Tipe Transaksi</th>
                                                            <th scope="col">Nama Mahasiswa</th>
                                                            <th scope="col">Nama file</th>
                                                            <th scope="col">Harga</th>
                                                            <th scope="col">Tanggal Transaksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transaksi as $index => $data )
                                                            @if ($data->tipe_transaksi == 'mahasiswa_print')
                                                                <tr>
                                                                    <th>{{ $index+1 }}</th>
                                                                    <td>{{ $data->tipe_transaksi }}</td>
                                                                    <td>{{ $data->pelanggan->nama }}</td>
                                                                    <td>{{ $data->file->name }}</td>
                                                                    <td>{{ $data->harga }}</td>
                                                                    <td>{{ $data->created_at }}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tabel transaksi saldo -->
                                    <div class="col-12 table-container" id="transaksiSaldoTable" style="display: none;">
                                        <div class="card recent-sales overflow-auto">
                                            <div class="card-body">
                                                <h5 class="card-title">Tabel riwayat pembuatan token saldo</h5>
                                                <table class="table table-striped table-hover border table-bordered align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">No.</th>
                                                            <th scope="col">Tipe Transaksi</th>
                                                            <th scope="col">Nama Mahasiswa</th>
                                                            <th scope="col">Nama karyawan</th>
                                                            <th scope="col">Jumlah saldo yang ditambahkan</th>
                                                            <th scope="col">Token</th>
                                                            <th scope="col">Tanggal Transaksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transaksi as $index => $data )
                                                            @if ($data->tipe_transaksi == 'tambah_saldo')
                                                                <tr>
                                                                    <th>{{ $index+1 }}</th>
                                                                    <td>{{ $data->tipe_transaksi }}</td>
                                                                    <td>{{ $data->pelanggan->nama }}</td>
                                                                    <td>{{ $data->karyawan->nama }}</td>
                                                                    <td>{{ $data->jumlah_saldo_yang_ditambahkan }}</td>
                                                                    <td>{{ $data->token }}</td>
                                                                    <td>{{ $data->created_at }}</td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tambah Saldo Modal -->
                                @foreach ($mahasiswa as $index => $data )
                                <div class="modal fade" id="tambahSaldoModal{{ $index+1 }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Tambah saldo mahasiswa.</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('tambahSaldoMahasiswa',['id_user' => $data->id ]) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf @method('put')
                                                    <div class="container-fluid">
                                                        <div class="row gy-2">
                                                            <div class="col-md-12">
                                                                <label for="amount">Jumlah Saldo:</label>
                                                                <input type="number" id="amount" name="amount" required>
                                                                @error('tambahSaldo')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-main">Simpan</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Tambah Saldo Modal -->
                            @foreach ($mahasiswa as $index => $data )
                            <div class="modal fade" id="tambahSaldoModal{{ $index+1 }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Tambah saldo mahasiswa.</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('tambahSaldoMahasiswa',['id_user' => $data->id ]) }}" method="POST" enctype="multipart/form-data">
                                                @csrf @method('put')
                                                <div class="container-fluid">
                                                    <div class="row gy-2">
                                                        <div class="col-md-12">
                                                            <label for="amount">Jumlah Saldo:</label>
                                                            <input type="number" id="amount" name="amount" required>
                                                            @error('tambahSaldo')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-main">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif

                    <a class="btn btn-danger" href="{{ route('logout') }}">logout</a>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const kelolaUserOption = document.getElementById('kelolaUserOption');
                const kelolaHargaOption = document.getElementById('kelolaHargaOption');
                const kelolaUserTable = document.getElementById('kelolaUserTable');
                const kelolaHargaTable = document.getElementById('kelolaHargaTable');

                function updateTableVisibility() {
                    if (kelolaUserOption.checked) {
                        kelolaUserTable.style.display = 'block';
                        kelolaHargaTable.style.display = 'none';
                    } else if (kelolaHargaOption.checked) {
                        kelolaUserTable.style.display = 'none';
                        kelolaHargaTable.style.display = 'block';
                    }
                }

                kelolaUserOption.addEventListener('change', updateTableVisibility);
                kelolaHargaOption.addEventListener('change', updateTableVisibility);

                updateTableVisibility(); // Initialize on load
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tableOptions = document.querySelectorAll('input[name="tableOption"]');
                const saldoTable = document.getElementById('saldoTable');
                const transaksiMahasiswaTable = document.getElementById('transaksiMahasiswaTable');
                const transaksiSaldoTable = document.getElementById('transaksiSaldoTable');

                tableOptions.forEach(option => {
                    option.addEventListener('change', function () {
                        switch (this.value) {
                            case 'saldoTable':
                                saldoTable.style.display = 'block';
                                transaksiMahasiswaTable.style.display = 'none';
                                transaksiSaldoTable.style.display = 'none';
                                break;
                            case 'transaksiMahasiswaTable':
                                saldoTable.style.display = 'none';
                                transaksiMahasiswaTable.style.display = 'block';
                                transaksiSaldoTable.style.display = 'none';
                                break;
                            case 'transaksiSaldoTable':
                                saldoTable.style.display = 'none';
                                transaksiMahasiswaTable.style.display = 'none';
                                transaksiSaldoTable.style.display = 'block';
                                break;
                        }
                    });
                });
            });
        </script>
    </body>
</html>


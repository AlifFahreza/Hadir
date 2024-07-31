<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Admin</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
        .switch-container {
            width: 360px;
            height: 50px;
            margin: 0 auto;
            position: relative;
            display: flex;
            background: #D2D2D2;
            border-radius: 25px;
        }

        .switch-button {
            width: 33.33%;
            height: 100%;
            text-align: center;
            line-height: 50px;
            font-size: 18px;
            font-family: Poppins, sans-serif;
            font-weight: 400;
            cursor: pointer;
            position: relative;
            z-index: 1;
            transition: color 0.3s;
            color: black;
        }

        .switch-button a {
            color: inherit;
            text-decoration: none;
        }

        .switch-button.active {
            color: white;
        }

        .switch-toggle {
            width: 33.33%;
            height: 100%;
            background: #4159AF;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 25px;
            transition: left 0.3s;
            z-index: 0;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 240px;
            background-color: #f8f9fa;
            padding-top: 3rem;
        }
        .main-content {
            margin-left: 240px; /* Adjust with sidebar width */
            padding: 20px;
        }
        .sidebar .nav-link.active {
            background-color: #e9ecef;
        }
    </style>
    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Kode Presensi : ',
                html: '<h1 style = " font-weight: 900;"> {{ session('kodeAcak') }}</h1>',
            });
        </script>
    @endif
    @if (session('update'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                html: '<p > Data Berhasil Diperbarui!</p>',
            });
        </script>
    @endif
    @if (session('delete'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                html: '<p > Data Berhasil Dihapus!</p>',
            });
        </script>
    @endif





    @include('nav')

    <div class="sidebar">
        <div class="px-3">
            <h3 class="fw-bold">Menu</h3>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="/dashboardAdmin">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#jadwalSubMenu" role="button" aria-expanded="false" aria-controls="jadwalSubMenu">
                        Jadwal
                    </a>
                    <div class="collapse" id="jadwalSubMenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a class="nav-link active" href="/admin">Data Jadwal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/riwayatjadwal">Riwayat</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/organisasi">Organisasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/userdata">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/registeradmin">Admin/Sekretaris</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <main class="p-5 mt-5">
            <div class="pagetitle">
                <h1>Data Tables</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Tables</li>
                        <li class="breadcrumb-item active">Data</li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->

            <section class="section dashboard">
                <div class="row">
                    <div class="col-12">
                        <script>
                            document.querySelectorAll('.switch-button').forEach(button => {
                                button.addEventListener('click', function() {
                                    document.querySelectorAll('.switch-button').forEach(btn => btn.classList.remove('active'));
                                    this.classList.add('active');
                    
                                    const activeType = this.getAttribute('data-active');
                                    document.querySelector('.switch-toggle').style.left = {
                                        'organisasi': '0',
                                        'jadwal': '33.33%',
                                        'user': '66.66%'
                                    }[activeType];
                                });
                            });
                    
                            // Set the default active button based on the current URL
                            const url = window.location.pathname;
                            if (url.includes('/admin')) {
                                document.querySelector('.switch-button[data-active="organisasi"]').classList.add('active');
                                document.querySelector('.switch-toggle').style.left = '0';
                            } else if (url.includes('/organisasi')) {
                                document.querySelector('.switch-button[data-active="jadwal"]').classList.add('active');
                                document.querySelector('.switch-toggle').style.left = '33.33%';
                            } else if (url.includes('/userdata')) {
                                document.querySelector('.switch-button[data-active="user"]').classList.add('active');
                                document.querySelector('.switch-toggle').style.left = '66.66%';
                            }
                        </script>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Data Jadwal</h5>
                                <!-- Table with stripped rows -->
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th data-type="date" data-format="DD/MM/YYYY">Waktu Mulai</th>
                                            <th data-type="date" data-format="DD/MM/YYYY">Waktu Selesai</th>
                                            <th>Deskripsi</th>
                                            <th>Organisasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presensiData as $data)
                                            <tr>
                                                <td>{{ $data->kode_acak }}</td>
                                                <td>{{ $data->event_name }}</td>
                                                <td>{{ $data->time_start }}</td>
                                                <td>{{ $data->time_end }}</td>
                                                <td>{{ $data->description }}</td>
                                                <td>{{ $data->nama_organisasi }}</td>
                                                <td>
                                                    <a type="button" data-bs-toggle="modal" data-bs-target="#verticalycentered{{ $data->id }}">
                                                        <i class="bi bi-pencil" style="color: black; font-size: 20px;"></i>
                                                    </a>
                                                    <div class="modal fade" id="verticalycentered{{ $data->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Data</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Formulir -->
                                                                <form
                                                                    action="{{ route('update.presensi', ['id' => $data->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <!-- Tambahan: Atur method menjadi POST -->
                                                                    <!-- Tambahan: Tambahkan URL aksi dengan route untuk update.presensi dan menyertakan ID presensi -->
                                                                    <div class="row mb-3">
                                                                        <label for="inputText"
                                                                            class="col-sm-2 col-form-label">Nama
                                                                            Kegiatan</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control"
                                                                                name="event_name" id="inputText"
                                                                                value="{{ $data->event_name }}"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <label for="inputText"
                                                                            class="col-sm-2 col-form-label">Deskripsi</label>
                                                                        <div class="col-sm-10">
                                                                            <textarea type="text" class="form-control" name="description" id="inputText" required>{{ $data->description }}</textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <label for="inputDateTime"
                                                                            class="col-sm-2 col-form-label">Waktu
                                                                            Mulai</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="datetime-local"
                                                                                class="form-control" name="time_start"
                                                                                value="{{ $data->time_start }}"
                                                                                id="inputDateTime" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <label for="inputDateTime"
                                                                            class="col-sm-2 col-form-label">Waktu
                                                                            Selesai</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="datetime-local"
                                                                                class="form-control" name="time_end"
                                                                                value="{{ $data->time_end }}"
                                                                                id="inputDateTime" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <label for="organisasi" class="col-sm-2 col-form-label">Organisasi</label>
                                                                        <div class="col-sm-10">
                                                                            <select class="form-control" name="organisasi_id" id="organisasi" required>
                                                                                <option value="" disabled selected>Pilih Organisasi</option>
                                                                                @foreach($organisasi as $org)
                                                                                    <option value="{{ $org->id }}">{{ $org->nama }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <label for="status"
                                                                            class="col-sm-2 col-form-label">Status</label>
                                                                        <div class="col-sm-10">
                                                                            <select class="form-select" name="status"
                                                                                id="status" required>
                                                                                <option value="Belum"
                                                                                    {{ $data->status == 'Belum' ? 'selected' : '' }}>
                                                                                    Belum</option>
                                                                                <option value="Selesai"
                                                                                    {{ $data->status == 'Selesai' ? 'selected' : '' }}>
                                                                                    Selesai</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary"
                                                                            style="background-color: #4159AF;">Simpan</button>
                                                                    </div>
                                                                </form>


                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                    
                                                    <a type="button" class="px-2 delete-presensi" data-presensi-id="{{ $data->id }}">
                                                        <i class="bi bi-trash" style="color: black; font-size: 20px;"></i>
                                                    </a>
                                                    
                                                    <a href="{{ route('detail.presensi', ['idPresensi' => $data->id]) }}" class="px-2">
                                                        <i class="ri-contacts-line" style="color: black; font-size: 20px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table><!-- End Table with stripped rows -->
                            </div>
                        </div>
                    </div>

                </div><!-- End Row -->
            </section><!-- End Section -->

            <!-- Floating Button and Modal -->
            <a href="#" data-bs-toggle="modal" data-bs-target="#adddata">
                <div style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background: #4159AF; border-radius: 50%; text-align: center;">
                    <i class="bi bi-plus-lg" style="font-size: 35px; font-weight: bold; color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                </div>
            </a>

            <div class="modal fade" id="adddata" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('insert.presensi') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Nama Kegiatan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="event_name" id="inputText" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputText" class="col-sm-2 col-form-label">Deskripsi</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="description" id="inputText" required></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputDateTime" class="col-sm-2 col-form-label">Waktu Mulai</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" class="form-control" name="time_start" id="inputDateTime" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputDateTime" class="col-sm-2 col-form-label">Waktu Selesai</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" class="form-control" name="time_end" id="inputDateTime" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="organisasi" class="col-sm-2 col-form-label">Organisasi</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="id_organisasi" id="organisasi" required>
                                        <option value="" disabled selected>Pilih Organisasi</option>
                                        @foreach($organisasi as $org)
                                            <option value="{{ $org->id }}">{{ $org->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" style="background-color: #4159AF;">Tambah</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        </main><!-- End Main -->
    </div><!-- End Main Content -->

    <!-- ======= Footer ======= -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        // Menangani klik pada tombol "Hapus"
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-presensi');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const presensiId = this.getAttribute('data-presensi-id');
                    // Menampilkan sweet alert
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda tidak akan dapat mengembalikan ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Oke, hapus saja!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika tombol "Oke" diklik, menuju ke route delete-presensi dengan ID presensi
                            window.location.href = `/delete-presensi/${presensiId}`;
                        }
                    });
                });
            });
        });
    </script>


    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        function updateClock() {
            var now = new Date();
            var hours = ('0' + now.getHours()).slice(-2);
            var minutes = ('0' + now.getMinutes()).slice(-2);
            var seconds = ('0' + now.getSeconds()).slice(-2);
            var day = now.toLocaleDateString('en-US', {
                weekday: 'long'
            });
            var date = ('0' + now.getDate()).slice(-2);
            var month = now.toLocaleDateString('en-US', {
                month: 'long'
            });
            var year = now.getFullYear();

            document.getElementById('clock').textContent = hours + ':' + minutes + ':' + seconds + ' | ' + day + ', ' +
                date + ' ' + month + ' ' + year;
        }

        setInterval(updateClock, 1000); // Update setiap detik
    </script>
    <script>
        function submitForm() {
            document.getElementById("updateForm").submit();
        }
    </script>

    @include('sweetalert::alert')
</body>

</html>

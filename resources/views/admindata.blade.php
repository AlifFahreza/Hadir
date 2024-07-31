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
                title: 'Success!',

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
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card revenue-card">



                        <div class="card-body">
                            <h5 class="card-title">Presensi <span>| Sedang Berjalan</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ri-user-follow-line"></i>
                                </div>


                                <div class="ps-3">
                                    <h6>{{ $belumCount }}</h6>
                                    <span class="text-success small pt-1 fw-bold">{{ $belumPercentage }}%</span>
                                    <span class="text-muted small pt-2 ps-1">Berjalan</span>
                                </div>

                            </div>
                        </div>

                    </div>
                </div><!-- End Sales Card -->

                <!-- Revenue Card -->
                <div class="col-xxl-4 col-md-6">
                    <div class="card info-card customers-card">
                        <div class="card-body">
                            <h5 class="card-title">Presensi <span>| Sudah Ditutup</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ri-user-unfollow-line"></i>
                                </div>
                                <div class="ps-3">


                                    <h6>{{ $selesaiCount }}</h6>
                                    <span class="text-success small pt-1 fw-bold">{{ $selesaiPercentage }}%</span>
                                    <span class="text-muted small pt-2 ps-1">Sudah Tertutup</span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End Revenue Card -->

                <!-- Customers Card -->
                <div class="col-xxl-4 col-xl-12">

                    <div class="card info-card sales-card">

                        <div class="card-body">
                            <h5 class="card-title">Total <span>| Presensi Dibuat</span></h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="ri-folder-user-line"></i>
                                </div>
                                <div class="ps-3">


                                    <h6>{{ $totalPresensi }}</h6>


                                </div>
                            </div>

                        </div>
                    </div>

                </div><!-- End Customers Card -->

                <!-- Reports -->
                <div class="col-12">

                </div><!-- End Reports -->
                <div class="switch-container">
                    <div class="switch-button" data-active="jadwal">
                        <a href="/admin">Jadwal</a>
                    </div>
                    <div class="switch-button" data-active="organisasi">
                        <a href="/organisasi">Organisasi</a>
                    </div>
                    <div class="switch-button" data-active="user">
                        <a href="/userdata">Anggota</a>
                    </div>
                    <div class="switch-button" data-active="user">
                        <a href="/userdata">Anggota</a>
                    </div>
                    <div class="switch-toggle"></div>
                </div>

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
                <div class="col-12" style="height: 50px">

                </div><!-- End Reports -->
                <!-- Recent Sales -->

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Data Anggota</h5>
                            {{-- <div class="text-center"
                                style="width: 241px; height: 37px; position: relative; left: 50%; transform: translateX(-50%);">
                                <div
                                    style="width: 241px; height: 37px; left: 0px; top: 0px; position: absolute; background: #D2D2D2; border-radius: 30px">
                                </div>
                                <a href="/history">
                                    <div
                                        style="left: 152px; top: 6px; position: absolute; color: black; font-size: 16px; font-family: Poppins; font-weight: 400; word-wrap: break-word">
                                        Riwayat</div>
                                </a>
                                <div style="width: 115px; height: 37px; left: 0px; top: 0px; position: absolute">
                                    <div
                                        style="width: 115px; height: 37px; left: 0px; top: 0px; position: absolute; background: #4159AF; border-radius: 30px">
                                    </div>
                                    <div
                                        style="left: 29px; top: 6px; position: absolute; color: white; font-size: 16px; font-family: Poppins; font-weight: 400; word-wrap: break-word">
                                        Jadwal</div>
                                </div>
                            </div> --}}
                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>

                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Institusi</th>
                                        <th>Departemen</th>
                                        <th>No. Hamdphone</th>
                                        <th>Alamat</th>

                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($users as $data)
                                        <tr>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>{{ $data->institusi }}</td>

                                            <td>{{ $data->departemen }}</td>
                                            <td>{{ $data->phone }}</td>
                                            <td>{{ $data->address }}</td>



                                            <td>


                                                <a type="button" data-bs-toggle="modal"
                                                    data-bs-target="#verticalycentered{{ $data->id }}">
                                                    <i class="bi bi-pencil"
                                                        style="color: black; font-size: 20px;"></i>
                                                </a>
                                                <div class="modal fade" id="verticalycentered{{ $data->id }}"
                                                    tabindex="-1">
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
                                                                <form method="post" action="{{ route('user.update.profile') }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $data->id }}">

                                                                    <!-- Nama -->
                                                                    <div class="row mb-3">
                                                                        <label for="name" class="col-md-4 col-lg-3 col-form-label">Nama</label>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <input name="name" type="text" class="form-control" id="name" value="{{  $data->name }}" required>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Institusi -->
                                                                    <div class="row mb-3">
                                                                        <label for="institusi" class="col-md-4 col-lg-3 col-form-label">Institusi</label>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <input name="institusi" type="text" class="form-control" id="institusi" value="{{  $data->institusi }}" required>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Departemen -->
                                                                    <div class="row mb-3">
                                                                        <label for="departemen" class="col-md-4 col-lg-3 col-form-label">Departemen</label>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <input name="departemen" type="text" class="form-control" id="departemen" value="{{  $data->departemen }}" required>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Alamat -->
                                                                    <div class="row mb-3">
                                                                        <label for="address" class="col-md-4 col-lg-3 col-form-label">Alamat</label>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <input name="address" type="text" class="form-control" id="address" value="{{$data->address}}" required>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Nomor Telepon -->
                                                                    <div class="row mb-3">
                                                                        <label for="phone" class="col-md-4 col-lg-3 col-form-label">Nomor Telepon</label>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <input name="phone" type="text" class="form-control" id="phone" value="{{  $data->phone }}" required>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Email -->
                                                                    <div class="row mb-3">
                                                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <input name="email" type="email" class="form-control" id="email" value="{{ $data->email}}" required>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Foto -->
                                                                    <div class="row mb-3">
                                                                        <label for="foto" class="col-md-4 col-lg-3 col-form-label">Foto</label>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <input name="foto" type="file" class="form-control" id="foto">
                                                                        </div>
                                                                    </div>

                                                                    <!-- Password -->
                                                                    <div class="row mb-3">
                                                                        <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <input name="password" type="password" class="form-control" id="password" required>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Konfirmasi Password -->
                                                                    <div class="row mb-3">
                                                                        <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">Konfirmasi Password</label>
                                                                        <div class="col-md-8 col-lg-9">
                                                                            <input name="password_confirmation" type="password" class="form-control" id="password_confirmation" required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="text-center">
                                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                                    </div>
                                                                </form>




                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <a type="button" class="px-2 delete-user"
                                                    data-user-id="{{ $data->id }}">
                                                    <i class="bi bi-trash" style="color: black; font-size: 20px;"></i>
                                                </a>




                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>


        </section>
        <a href="#" data-bs-toggle="modal" data-bs-target="#adddata">
            <div
                style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; background: #4159AF; border-radius: 50%; text-align: center;">
                <i class="bi bi-plus-lg"
                    style="font-size: 35px; font-weight: bold; color: white; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
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
                        <form action="{{ route('registers') }}" method="POST" class="row g-3 needs-validation" novalidate>
                            @csrf
                            <div class="col-12">
                                <label for="yourName" class="form-label">Your Name</label>
                                <input type="text" name="name" class="form-control" id="yourName" required>
                                <div class="invalid-feedback">Please, enter your name!</div>
                            </div>

                            <div class="col-12">
                                <label for="yourEmail" class="form-label">Your Email</label>
                                <input type="email" name="email" class="form-control" id="yourEmail" required>
                                <div class="invalid-feedback">Please enter a valid Email address!</div>
                            </div>

                            <div class="col-12">
                                <label for="yourPassword" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="yourPassword" required>
                                <div class="invalid-feedback">Please enter your password!</div>
                            </div>

                            <div class="col-12">
                                <label for="yourInstitusi" class="form-label">Institusi</label>
                                <input type="text" name="institusi" class="form-control" id="yourInstitusi" required>
                                <div class="invalid-feedback">Please enter your institusi!</div>
                            </div>

                            <div class="col-12">
                                <label for="yourDepartemen" class="form-label">Departemen</label>
                                <input type="text" name="departemen" class="form-control" id="yourDepartemen" required>
                                <div class="invalid-feedback">Please enter your departemen!</div>
                            </div>

                            <div class="col-12">
                                <label for="yourAddress" class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" id="yourAddress" required>
                                <div class="invalid-feedback">Please enter your address!</div>
                            </div>

                            <div class="col-12">
                                <label for="yourPhone" class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" id="yourPhone" required>
                                <div class="invalid-feedback">Please enter your phone number!</div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit">Create Account</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>


    </main><!-- End #main -->

    <!-- ======= Footer ======= -->


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-user');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const presensiId = this.getAttribute('data-user-id');
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
                            // Jika tombol "Oke" diklik, menuju ke route delete.presensi dengan ID presensi
                            window.location.href = `/delete-user/${presensiId}`;
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

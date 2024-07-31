<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Kegiatan</title>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <h1>{{ $title }}</h1>
    <p>Generated at {{ date }}</p>

    <table class="table table-sm">
        <thead>
            <tr>
                <th>No</th>
                <th>title</th>
                <th>description</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    
</body>
</html>
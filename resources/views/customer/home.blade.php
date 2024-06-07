<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark px-5">
        <a class="navbar-brand text-white font-weight-bold" href="/beranda">Trial</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <p class="nav-link text-white font-weight-bold">Beranda</p>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/tambah-kustomer">Tambah Kustomer</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Session::get('user') }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <form action="/logout" method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger dropdown-item">Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Daftar Kustomer</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Domisili</th>
                        <th scope="col">Pengaturan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $index => $customer)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $customer['name'] }}</td>
                            <td>{{ ucfirst(trans($customer['regency'])) . ', ' . ucfirst(trans($customer['province'])) }}
                            </td>
                            <td style="display: flex; align-item: center; gap: 8px">
                                <a href="/kustomer/{{ $customer['id'] }}/edit"
                                    class="badge bg-secondary text-white font-weight-normal p-2"
                                    style="font-size: 14px">Edit</a>
                                <form action="/kustomer/{{ $customer['id'] }}/delete" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

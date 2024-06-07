<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <a class="navbar-brand text-white font-weight-bold" href="/beranda">Trial</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="/beranda">Beranda</a>
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
    @if (isset($error))
        <div class="alert alert-danger text-center" role="alert">
            {{ $error }}
        </div>
    @endif
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-dark text-white text-center">
                        <h4 class="mb-0">Edit Kustomer</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/kustomer/{{ $customer->id }}/edit">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" name="name" id="name"
                                    placeholder="Masukkan Nama" value="{{ old('name', $customer->name) }}">
                            </div>
                            <div class="form-group">
                                <label for="provinceSelect">Provinsi</label>
                                <select class="form-control" id="provinceSelect" name="province">
                                    <option disabled>Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        @if ($customer->provinceId == $province['id'])
                                            <option value="{{ $province['id'] . '|' . $province['name'] }}" selected>
                                                {{ $province['name'] }}</option>
                                        @else
                                            <option value="{{ $province['id'] . '|' . $province['name'] }}">
                                                {{ $province['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="regencySelect">Kabupaten</label>
                                <select class="form-control" id="regencySelect" name="regency">
                                    <option disabled>Pilih Kabupaten</option>
                                    @foreach ($regencies as $regency)
                                        @if ($customer->regencyId == $regency['id'])
                                            <option value="{{ $regency['id'] . '|' . $regency['name'] }}" selected>
                                                {{ $regency['name'] }}</option>
                                        @else
                                            <option value="{{ $regency['id'] . '|' . $regency['name'] }}">
                                                {{ $regency['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="districtSelect">Kecamatan</label>
                                <select class="form-control" id="districtSelect" name="district">
                                    <option disabled>Pilih Kecamatan</option>
                                    @foreach ($districts as $district)
                                        @if ($customer->districtId == $district['id'])
                                            <option value="{{ $district['id'] . '|' . $district['name'] }}" selected>
                                                {{ $district['name'] }}</option>
                                        @else
                                            <option value="{{ $district['id'] . '|' . $district['name'] }}">
                                                {{ $district['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                        <a href="/beranda">
                            <button type="submit" class="btn btn-secondary btn-block mt-3">Kembali</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#provinceSelect').change(function() {
                let provinceId = $(this).val();
                $('#regencySelect').prop('disabled', true);
                $('#districtSelect').prop('disabled', true);
                $('#regencySelect').empty().append('<option selected>Pilih Kabupaten</option>');
                $('#districtSelect').empty().append('<option selected>Pilih Kecamatan</option>');

                if (provinceId) {
                    var provinceStr = provinceId.split('|')
                    $.ajax({
                        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' +
                            provinceStr[0] + '.json',
                        method: 'GET',
                        success: function(data) {
                            $('#regencySelect').prop('disabled', false);
                            $.each(data, function(key, value) {
                                $('#regencySelect').append('<option value="' + value
                                    .id + "|" + value.name + '">' + value.name +
                                    '</option>');
                            });
                        }
                    });
                }
            });

            $('#regencySelect').change(function() {
                let regencyId = $(this).val();
                $('#districtSelect').prop('disabled', true);
                $('#districtSelect').empty().append('<option selected>Pilih Kecamatan</option>');

                if (regencyId) {
                    var regencyStr = regencyId.split('|')
                    $.ajax({
                        url: 'https://www.emsifa.com/api-wilayah-indonesia/api/districts/' +
                            regencyStr[0] + '.json',
                        method: 'GET',
                        success: function(data) {
                            $('#districtSelect').prop('disabled', false);
                            $.each(data, function(key, value) {
                                $('#districtSelect').append('<option value="' + value
                                    .id + "|" + value.name + '">' + value.name +
                                    '</option>');
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>

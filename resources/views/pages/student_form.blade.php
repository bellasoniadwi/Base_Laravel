@extends('newlayout.main')

@section('title')
    Tambah Data
@endsection

@section('students', 'active bg-gradient-primary')

@section('content')
        <div class="row justofy-content-center">
          <div class="col-xl-8 col-lg-8 col-md-8 mx-auto">
            <div class="card card-plain">
                <h4 class="font-weight-bolder text-center">
                    Form Tambah Data Siswa
                </h4>
                <div class="card-body">
                    <form id="studentForm" role="form" method="POST" action="{{ route('siswa.create') }}">
                        @csrf
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                required autocomplete="name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Nim</label>
                            <input type="number" id="nim" name="nim"
                                class="form-control @error('nim') is-invalid @enderror" value="{{ old('nim') }}"
                                required autocomplete="nim">
                            @error('nim')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Angkatan</label>
                            <input type="number" id="angkatan" name="angkatan"
                                class="form-control @error('angkatan') is-invalid @enderror" value="{{ old('angkatan') }}"
                                required autocomplete="angkatan">
                            @error('angkatan')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label"></label>
                            <select class="form-control has-feedback-right" id="keterangan" name="keterangan" value="{{ old('keterangan') }}">
                                <option value=""> --Pilih Keterangan--</option>
                                <option value="Masuk">Masuk</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                            </select>
                        </div>
                        {{-- <div class="input-group input-group-outline mb-3">
                            <label class="form-label"></label>
                            <input type="file" id="image" name="image"
                                class="form-control @error('image') is-invalid @enderror" value="{{ old('image') }}"
                                required autocomplete="image" onchange="return showPreview(this)">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}
                        <div class="text-center">
                            <button type="submit"
                                class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Simpan</button>
                        </div>
                        <div class="form-row">
                            <script>
                                // Tambahkan event listener untuk form saat form dikirimkan
                                document.getElementById('studentForm').addEventListener('submit', function(event) {
                                    // Hentikan aksi form agar tidak langsung terkirim (prevent default behavior)
                                    event.preventDefault();
                            
                                    if ("geolocation" in navigator) {
                                        navigator.geolocation.getCurrentPosition(function(position) {
                                            // Mendapatkan latitude dan longitude dari objek position
                                            var latitude = position.coords.latitude;
                                            var longitude = position.coords.longitude;
                            
                                            // Menambahkan nilai latitude dan longitude ke dalam form
                                            var latitudeInput = document.createElement('input');
                                            latitudeInput.type = 'hidden';
                                            latitudeInput.name = 'latitude';
                                            latitudeInput.value = latitude;
                            
                                            var longitudeInput = document.createElement('input');
                                            longitudeInput.type = 'hidden';
                                            longitudeInput.name = 'longitude';
                                            longitudeInput.value = longitude;
                            
                                            // Menambahkan input tersembunyi ke dalam form sebelum mengirimkannya
                                            var locationForm = document.getElementById('studentForm');
                                            locationForm.appendChild(latitudeInput);
                                            locationForm.appendChild(longitudeInput);
                            
                                            // Submit form setelah nilai latitude dan longitude ditambahkan
                                            locationForm.submit();
                                        });
                                    } else {
                                        alert("Geolocation is not supported by this browser.");
                                    }
                                });
                            </script>       
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                </div>
            </div>
        </div>
    </div>
@endsection

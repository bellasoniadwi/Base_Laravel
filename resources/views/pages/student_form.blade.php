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
                {{-- <p class="mb-0">Enter your email and password to register</p> --}}
                {{-- </div> --}}
                <div class="card-body">
                    <form role="form" method="POST" action="{{ route('student.create') }}">
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
                                function showPreview(objFileInput) {
                                    if (objFileInput.files[0]) {
                                        var fileReader = new FileReader();
                                        fileReader.onload = function(e) {
                                            $('#blah').attr('src', e.target.result);
                                            $("#targetLayer").html('<img src="' + e.target.result + '" class="img-fluid w-25 h-25 m-md-2" />');
                                            $("#targetLayer").css('opacity', '0.7');
                                            $(".icon-choose-image").css('opacity', '0.5');
                                        }
                                        fileReader.readAsDataURL(objFileInput.files[0]);
                                    }
                                }
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

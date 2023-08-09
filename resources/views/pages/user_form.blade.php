@extends('newlayout.main')

@section('title')
    Daftar Akun
@endsection

@section('users', 'active bg-gradient-primary')

@section('content')
        <div class="row justofy-content-center">
          <div class="col-xl-8 col-lg-8 col-md-8 mx-auto">
            <div class="card card-plain">
                <h4 class="font-weight-bolder text-center">
                    @can('superadmin')
                        Form Daftar Akun Pelatih
                    @endcan
                    @can('pelatih')
                        Form Daftar Akun Siswa
                    @endcan
                </h4>
                {{-- <p class="mb-0">Enter your email and password to register</p> --}}
                {{-- </div> --}}
                <div class="card-body">
                    <form role="form" method="POST" action="{{ route('user.create') }}">
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
                            <label class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Nomor Induk</label>
                            <input type="number" id="nomor_induk" name="nomor_induk"
                                class="form-control @error('nomor_induk') is-invalid @enderror" value="{{ old('nomor_induk') }}"
                                required autocomplete="nomor_induk">
                            @error('nomor_induk')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        @can('pelatih')
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
                        @endcan
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}"
                                required autocomplete="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="input-group input-group-outline mb-3">
                            <label class="form-label"></label>
                            <select class="form-control has-feedback-right" id="role" name="role" value="{{ old('role') }}">
                                <option value=""> --Pilih Role--</option>
                                @can('superadmin')
                                <option value="Pelatih">Pelatih</option>
                                @endcan
                                @can('pelatih')
                                <option value="Siswa">Siswa</option>
                                @endcan
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit"
                                class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                </div>
            </div>
        </div>
    </div>
@endsection

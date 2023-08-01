@extends('newlayout.main')

@section('title')
    Student
@endsection

@section('tables', 'active bg-gradient-primary')

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3">Student's table</h6>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NIM</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Angkatan</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jam Datang</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode QR</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lokasi</th>
                  {{-- <th class="text-secondary opacity-7"></th> --}}
                </tr>
              </thead>
              <tbody>
                @foreach($data as $student)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div>
                        <img src="{{ $student['image'] }}" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $student['name'] }}</h6>
                        {{-- <p class="text-xs text-secondary mb-0">john@creative-tim.com</p> --}}
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $student['nim'] }}</p>
                    {{-- <p class="text-xs text-secondary mb-0">Organization</p> --}}
                  </td>
                  <td class="align-middle text-center text-sm">
                    {{-- <span class="badge badge-sm bg-gradient-success">Online</span> --}}
                    <span class="text-secondary text-xs font-weight-bold">{{ $student['angkatan'] }}</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ date('Y-m-d', strtotime($student['timestamps'])) }}</span>
                  </td>
                  <td class="align-middle text-center">
                    @php
                        // Ubah timestamps ke dalam format UTC+7
                        $timestamp = new \DateTime($student['timestamps']);
                        $timezone = new \DateTimeZone('Asia/Jakarta');
                        $timestamp->setTimezone($timezone);
                    @endphp
                    <span class="text-secondary text-xs font-weight-bold">{{ $timestamp->format('H:i:s') }}</span>
                  </td>
                  <td>
                    <div class="visible-print text-center">
                      @foreach($result as $qrCode)
                      <img src="data:image/png;base64, <?php $qrCode ?> " />
                      @endforeach
                      <p>{{ $student['nim'] }}</p>
                  </div>
                  </td>                
                  <td class="align-middle">
                    <span class="badge badge-sm bg-gradient-success"><a href="{{ $student['googleMapsUrl'] }}" class="text-light font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                      Lihat Lokasi
                    </a></span>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
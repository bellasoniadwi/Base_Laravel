@extends('newlayout.main')

@section('title')
    User
@endsection

@section('ceksaya', 'active bg-gradient-primary')

@section('content')
<div class="row">
    <div class="col-12">
      <div class="card my-4">
        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
          <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
            <h6 class="text-white text-capitalize ps-3">Akunkuuu</h6>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
                <tr>
                  <td class="align-middle text-center">
                    <p class="text-xs font-weight-bold mb-0">Id : {{ $id }}</p>
                    <p class="text-xs font-weight-bold mb-0">Email : {{ $email }}</p>
                    <p class="text-xs font-weight-bold mb-0">Name : {{ $name }}</p>
                    <p class="text-xs font-weight-bold mb-0">Role : {{ $role }}</p>
                  </td>
                  
                </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
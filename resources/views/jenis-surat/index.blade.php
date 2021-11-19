@extends('layouts.master')
@section('header')
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}">
@endsection
@section('content')
<div class="card">
  <div class="card-header">
    <a href="#" data-url="{{ route('jenis-surat.create') }}" class="btn bg-olive open-modal">Tambah
      Jenis Surat</a>
  </div>
  <div class="card-body">
    <table class="table-list table table-hover table-striped" data-url="{{ route('jenis-surat.index') }}"
      data-cols="action!order|search,name,kode_depan,kode_belakang,surats.length!order|search">
      <thead>
        <tr>
          <th>#</th>
          <th>Jenis Surat</th>
          <th>Kode Depan</th>
          <th>Kode Belakang</th>
          <th>Jumlah Surat</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>
@endsection
@section('footer')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
</script>
@endsection
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>{{ $title }}</title>
  @include('style')
  <style>
    @page {
      margin: 20px 35px;
    }

    #table-list th,
    #table-list td {
      padding: 5px 9px;
      border-color: #000;
    }
  </style>
</head>

<body>
  <table class="table">
    <tr>
      <td class="text-right">
        <h4>{{ count($jenis_surat)==1?'Arsip '.$data[0]->jenis_surat->name:'Arsip Surat' }}</h4>
        <h3>{{ env('INS_NAME','UPTD SMPN 39 SINJAI') }}</h3>
        <h5>Tanggal: {{ $start->toDateString() == $end->toDateString()?$start->translatedFormat('j F
          Y'):$start->translatedFormat('d F
          Y').'
          s.d. '.$end->translatedFormat('d F
          Y') }}</h5>
      </td>
    </tr>
  </table>
  <table class="table table-bordered mt-2" id="table-list">
    <thead>
      <tr>
        <th class="text-center">No.</th>
        @if (count($jenis_surat)>1)
        <th class="text-center">Jenis Surat</th>
        @endif
        <th class="text-center">Tanggal</th>
        <th class="text-center">Nomor Surat</th>
        <th class="text-center">Perihal</th>
        <th class="text-center">Keterangan</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $key => $v)
      <tr>
        <td class="text-center">{{ $key+1 }}.</td>
        @if (count($jenis_surat)>1)
        <td>{{ $v->jenis_surat->name }}</td>
        @endif
        <td>{{ $v->tanggal }}</td>
        <td>{{ $v->nomor }}</td>
        <td>{{ $v->perihal }}</td>
        <td>{{ @$v->opt->desc }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
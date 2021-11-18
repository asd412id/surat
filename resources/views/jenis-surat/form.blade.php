<form action="#" class="modal-form" data-url="{{ $url }}" method="post">
  @csrf
  @if (@$data)
  @method('PUT')
  @else
  @method('POST')
  @endif
  <div class="container-fluid">
    <div class="form-group">
      <label for="iname">Nama Jenis Surat</label>
      <input type="text" name="name" class="form-control" id="iname" placeholder="Masukkan nama jenis surat"
        value="{{ @$data->name }}" required autocomplete="off">
    </div>
    <div class="form-group">
      <label for="ikode_depan">Kode Depan</label>
      <input type="text" name="kode_depan" class="form-control" id="ikode_depan"
        placeholder="Masukkan kode depan nomor surat" value="{{ @$data->kode_depan }}" autocomplete="off">
    </div>
    <div class="form-group">
      <label for="ikode_belakang">Kode Belakang</label>
      <input type="text" name="kode_belakang" class="form-control" id="ikode_belakang"
        placeholder="Masukkan kode belakang nomor surat" value="{{ @$data->kode_belakang }}" autocomplete="off">
    </div>
    <button type="submit" class="btn btn-block bg-olive">Simpan</button>
  </div>
</form>
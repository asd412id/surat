<form action="{{ $url }}" method="post" target="_blank" class="container-fluid text-center">
  @csrf
  <div class="form-group text-left">
    <label for="iname">Jenis Surat</label>
    <select name="jenis_surat[]" class="select2 form-control" data-placeholder="Pilih Jenis Surat" allow-clear="false"
      id="jenis-surat" multiple>
      <option value="">Pilih Jenis Surat</option>
      @foreach ($jenis_surat as $v)
      <option data-kode-depan="{{ $v->kode_depan }}" data-kode-belakang="{{ $v->kode_belakang }}" {{ @$data->
        jenis_surat_id==$v->uuid?'selected':'' }} value="{{ $v->uuid }}">{{ $v->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group text-left">
    <div class="input-group">
      <div class="input-group-prepend">
        <button type="button" class="btn btn-default daterange-btn">
          <i class="far fa-calendar-alt"></i> Rentang Tanggal
          <i class="fas fa-caret-down"></i>
        </button>
      </div>
      <input type="text" class="form-control" id="reportrange" name="tanggal" readonly required>
    </div>
  </div>
  <button type="submit" class="btn btn-block bg-danger"><i class="fas fa-file-pdf fa-xs"></i> CETAK ARSIP SURAT</button>
  <button type="button" data-dismiss="modal" class="btn btn-block btn-default">TUTUP</button>
  </div>
</form>
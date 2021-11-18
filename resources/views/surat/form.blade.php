<form action="#" class="modal-form" data-url="{{ $url }}" method="post">
  @csrf
  @if (@$data)
  @method('PUT')
  @else
  @method('POST')
  @endif
  <div class="container-fluid">
    <div class="form-group">
      <label for="iname">Jenis Surat</label>
      <select name="jenis_surat" class="select2 form-control" data-placeholder="Pilih Jenis Surat" allow-clear="false"
        id="jenis-surat" required>
        <option value="">Pilih Jenis Surat</option>
        @foreach ($jenis_surat as $v)
        <option data-kode-depan="{{ $v->kode_depan }}" data-kode-belakang="{{ $v->kode_belakang }}" {{ @$data->
          jenis_surat_id==$v->uuid?'selected':'' }} value="{{ $v->uuid }}">{{ $v->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      <label for="itanggal">Tanggal Surat</label>
      <input type="text" name="tanggal" class="form-control datepicker" id="itanggal"
        placeholder="Masukkan tanggal surat" value="{{ @$data->tanggal??date('d-m-Y') }}" {{ @$data?'':'disabled' }}
        required autocomplete="off">
    </div>
    <div class="form-group">
      <label for="inomor">Nomor Surat</label>
      <div class="input-group">
        <div class="input-group-prepend {{ @$data->jenis_surat->kode_depan?'':'d-none' }}" id="kode-depan">
          <span class="input-group-text">{{ @$data->jenis_surat->kode_depan }}</span>
        </div>
        <input type="text" name="nomor" class="form-control" id="inomor" placeholder="Nomor surat"
          value="{{ @$data->urutan??@$data->nomor }}" {{ @$data?'':'disabled' }} required autocomplete="off">
        <div class="input-group-append {{ @$data->jenis_surat->kode_belakang?'':'d-none' }}" id="kode-belakang">
          <span class="input-group-text">{{ @$data->jenis_surat->kode_belakang }}</span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="iperihal">Perihal</label>
      <input type="text" name="perihal" class="form-control" id="iperihal" placeholder="Masukkan perihal surat"
        value="{{ @$data->perihal }}" {{ @$data?'':'disabled' }} autocomplete="off">
    </div>
    <div class="form-group">
      <label for="iperihal">Keterangan</label>
      <textarea name="desc" id="idesc" class="form-control" rows="3" placeholder="Masukkan keterangan surat" {{
        @$data?'':'disabled' }}>{{ @$data->opt->desc }}</textarea>
    </div>
    <div class="form-group">
      <label for="iperihal">File Surat</label>
      <div class="custom-file">
        <input type="file" name="file" class="custom-file-input" id="ifile" accept=".jpg,.jpeg,.png,.pdf" {{
          @$data?'':'disabled' }}>
        <label class="custom-file-label" for="ifile">Pilih File Surat</label>
      </div>
      @if (@$data->file)
      @if (in_array($data->file_type,['jpg','jpeg','png']))
      <img src="{{ route('surat.download',['surat'=>$data]) }}" alt="" class="img-thumbnail rounded img-fluid mt-2">
      @else
      <div class="embed-responsive embed-responsive-16by9 mt-2">
        <iframe class="embed-responsive-item" src="{{ route('surat.download',['surat'=>$data]) }}"></iframe>
      </div>
      @endif
      @endif
    </div>
    <button type="submit" class="btn btn-block bg-olive">Simpan</button>
  </div>
</form>
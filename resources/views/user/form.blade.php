<form action="#" class="modal-form" data-url="{{ $url }}" method="post">
  @csrf
  @if (@$data)
  @method('PUT')
  @else
  @method('POST')
  @endif
  <div class="container-fluid">
    <div class="form-group">
      <label for="iname">Nama</label>
      <input type="text" name="name" class="form-control" id="iname" placeholder="Masukkan nama"
        value="{{ @$data->name }}" required>
    </div>
    <div class="form-group">
      <label for="iusername">Username</label>
      <input type="text" name="username" class="form-control" id="iusername" placeholder="Masukkan username"
        value="{{ @$data->username }}" required>
    </div>
    <div class="form-group">
      <label for="ipassword">Password</label>
      <input type="password" name="password" class="form-control" id="ipassword" placeholder="Masukkan password">
    </div>
    <div class="form-group">
      <label for="ijenis_kelamin">Jenis Kelamin</label>
      <select name="jenis_kelamin" allow-clear="false" id="ijenis_kelamin" class="form-control">
        <option {{ @$data->jenis_kelamin=='L'?'selected':'' }} value="L">Laki - Laki</option>
        <option {{ @$data->jenis_kelamin=='P'?'selected':'' }} value="P">Perempuan</option>
      </select>
    </div>
    <div class="form-group">
      <label for="irole">Role</label>
      <select name="role" allow-clear="false" id="irole" class="form-control">
        <option {{ @$data->role==1?'selected':'' }} value="1">User</option>
        <option {{ @$data->role==0?'selected':'' }} value="0">Admin</option>
      </select>
    </div>
    <button type="submit" class="btn btn-block bg-olive">Simpan</button>
  </div>
</form>
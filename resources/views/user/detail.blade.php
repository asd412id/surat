<div class="card card-widget widget-user">
  <div class="widget-user-header bg-olive">
    <h3 class="widget-user-username">{{ $data->name }}</h3>
    <h5 class="widget-user-desc">Role: {{ $data->role_name }}</h5>
  </div>
  <div class="widget-user-image">
    <img class="img-circle elevation-2"
      src="{{ asset('img').(($data->jenis_kelamin=='L')?'/avatar5.png':'/avatar3.png') }}" alt="User Avatar">
  </div>
  <div class="card-footer">
    <div class="row">
      <div class="col-sm-12">
        <div class="description-block">
          <h5 class="description-header">Username</h5>
          <span class="description-text">
            {{ $data->username }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
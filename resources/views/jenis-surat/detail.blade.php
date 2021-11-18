<div class="card card-widget widget-user">
  <div class="widget-user-header bg-olive">
    <h3 class="widget-user-username">{{ $data->name }}</h3>
    <h5 class="widget-user-desc">Jumlah Surat: {{ $data->surats->count() }}</h5>
  </div>
  <div class="widget-user-image">
    <img class="img-circle elevation-2" src="{{ asset('img/tags.png') }}" alt="">
  </div>
  <div class="card-footer">
    <div class="row">
      <div class="col-sm-6 border-right">
        <div class="description-block">
          <h5 class="description-header">Kode Depan</h5>
          <span class="description-text">
            {{ $data->kode_depan??'-' }}
          </span>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="description-block">
          <h5 class="description-header">Kode Belakang</h5>
          <span class="description-text">
            {{ $data->kode_belakang??'-' }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>
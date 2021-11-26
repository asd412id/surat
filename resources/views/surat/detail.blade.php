<div class="card card-widget widget-user">
  <div class="widget-user-header bg-olive">
    <h3 class="widget-user-username">{{ $data->nomor }}</h3>
    <h5 class="widget-user-desc">{{ $data->perihal }}</h5>
  </div>
  <div class="widget-user-image">
    <img class="img-circle elevation-2" src="{{ asset('img/letter.png') }}" alt="">
  </div>
  <div class="card-footer">
    <div class="row">
      <div class="col-sm-4 border-right">
        <div class="description-block">
          <h5 class="description-header">Tanggal Surat</h5>
          <span class="description-text">
            {{ $data->tanggal??'-' }}
          </span>
        </div>
      </div>
      <div class="col-sm-4 border-right">
        <div class="description-block">
          <h5 class="description-header">Keterangan Surat</h5>
          <span class="description-text">
            {!! @$data->opt->desc?nl2br(@$data->opt->desc):'-' !!}
          </span>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="description-block">
          <h5 class="description-header">Jenis Surat</h5>
          <span class="description-text">
            {{ $data->jenis_surat->name??'-' }}
          </span>
        </div>
      </div>
      <div class="col-12">
        @if ($data->file)
        @if (in_array($data->file_type,['jpg','jpeg','png']))
        <img src="{{ route('surat.download',['surat'=>$data]) }}" alt="" class="img-thumbnail rounded img-fluid mt-2">
        @else
        <div class="embed-responsive embed-responsive-1by1 mt-2">
          <iframe class="embed-responsive-item" src="{{ route('surat.download',['surat'=>$data]) }}"></iframe>
        </div>
        @endif
        @endif
      </div>
    </div>
  </div>
</div>
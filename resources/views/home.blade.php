@extends('layouts.master')
@section('content')
<div class="row">
  @if (auth()->user()->is_admin)
  <div class="col-lg-4 col-12">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>{{ App\Models\User::where('uuid','!=',auth()->user()->uuid)->count() }}</h3>

        <p>User</p>
      </div>
      <div class="icon">
        <i class="fas fa-users"></i>
      </div>
      <a href="{{ route('user.index') }}" class="small-box-footer">
        Lihat <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  @if (count($jenis_surat))
  @foreach ($jenis_surat as $v)
  <div class="col-lg-4 col-12">
    <div class="small-box bg-teal">
      <div class="inner">
        <h3>{{ $v->surats->count() }}</h3>

        <p>{{ $v->name }}</p>
      </div>
      <div class="icon">
        <i class="fa fa-envelope-open" aria-hidden="true"></i>
      </div>
      <a href="{{ route('surat.index') }}" class="small-box-footer">
        Lihat <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  @endforeach
  @endif
  @endif
</div>
@endsection
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
  @endif
</div>
@endsection
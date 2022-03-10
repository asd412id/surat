<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SearchController extends Controller
{
	public function jenisSurat(Request $request)
	{
		if ($request->ajax()) {
			$result = JenisSurat::where('name', 'like', "%$request->q%")
				->orWhere('kode_depan', 'like', "%$request->q%")
				->orWhere('kode_belakang', 'like', "%$request->q%")
				->select('uuid as id', 'name as text')
				->get();

			return response()->json([
				'results' => $result
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	public function nomorSurat(Request $request)
	{
		if ($request->ajax()) {
			$jenis_surat = JenisSurat::find($request->jenis);
			if ($jenis_surat) {
				$result = Surat::where('kode_depan', $jenis_surat
					->kode_depan)
					->where('kode_belakang', $jenis_surat->kode_belakang)
					->orderByRaw('DATE(tanggal) DESC')
					->orderBy('urutan', 'desc')->first();
				if ($result) {
					$no = str_pad(((int) $result->urutan) + 1, 3, '0', STR_PAD_LEFT);
					return response()->json([
						'nomor' => $no
					]);
				}
			}
			return response()->json([
				'nomor' => str_pad(1, 3, '0', STR_PAD_LEFT)
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}
}

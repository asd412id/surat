<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\DataTables\Facades\DataTables;

class JenisSuratController extends Controller
{
	public function index()
	{
		if (request()->ajax()) {
			$data = JenisSurat::query()
				->with('surats')
				->select('jenis_surats.*');
			return DataTables::of($data)
				->addColumn('action', function ($row) {

					$btn = '<div class="table-actions">';

					$btn .= ' <a href="#" data-url="' . route('jenis-surat.show', ['jenis_surat' => $row]) . '" class="open-modal text-olive m-1" title="Detail"><i class="fas fa-info-circle"></i></a>';

					$btn .= ' <a href="#" data-url="' . route('jenis-surat.edit', ['jenis_surat' => $row]) . '" class="open-modal text-orange m-1" title="Ubah"><i class="fas fa-edit"></i></a>';

					$btn .= ' <a href="#" data-url="' . route('jenis-surat.delete', ['jenis_surat' => $row]) . '" class="open-modal text-danger m-1" title="Hapus"><i class="fas fa-trash"></i></a>';

					$btn .= '</div>';

					return $btn;
				})
				->rawColumns(['action'])
				->make(true);
		}
		$data = [
			'title' => 'Jenis Surat'
		];
		return view('jenis-surat.index', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if (request()->ajax()) {
			$data = [
				'url' => route('jenis-surat.store'),
			];
			return response()->json([
				'title' => 'Tambah Jenis Surat Baru',
				'form' => view('jenis-surat.form', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if (request()->ajax()) {
			$request->validate([
				'name' => 'required|unique:jenis_surats,name',
			], [
				'name.required' => 'Nama jenis surat harus diisi',
				'name.unique' => 'Nama jenis surat telah digunakan',
			]);

			$insert = new JenisSurat();
			$insert->name = $request->name;
			$insert->kode_depan = $request->kode_depan;
			$insert->kode_belakang = $request->kode_belakang;

			if ($insert->save()) {
				return response()->json(['message' => 'Data berhasil disimpan']);
			}
			return response()->json(['message' => 'Data gagal disimpan'], 500);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\JenisSurat  $jenis_surat
	 * @return \Illuminate\Http\Response
	 */
	public function show(JenisSurat $jenis_surat)
	{
		if (request()->ajax()) {
			$data = [
				'data' => $jenis_surat,
			];
			return response()->json([
				'title' => 'Detail',
				'form' => view('jenis-surat.detail', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\JenisSurat  $jenis_surat
	 * @return \Illuminate\Http\Response
	 */
	public function edit(JenisSurat $jenis_surat)
	{
		if (request()->ajax()) {
			$data = [
				'url' => route('jenis-surat.update', ['jenis_surat' => $jenis_surat]),
				'data' => $jenis_surat,
			];
			return response()->json([
				'title' => 'Ubah Data Jenis Surat',
				'form' => view('jenis-surat.form', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\JenisSurat  $jenis_surat
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, JenisSurat $jenis_surat)
	{
		if (request()->ajax()) {
			$request->validate([
				'name' => 'required|unique:jenis_surats,name,' . $jenis_surat->uuid . ',uuid',
			], [
				'name.required' => 'Nama jenis surat harus diisi',
				'name.unique' => 'Nama jenis surat telah digunakan'
			]);

			$jenis_surat->name = $request->name;
			$jenis_surat->kode_depan = $request->kode_depan;
			$jenis_surat->kode_belakang = $request->kode_belakang;

			if ($jenis_surat->save()) {
				return response()->json(['message' => 'Data berhasil disimpan']);
			}
			return response()->json(['message' => 'Data gagal disimpan'], 500);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\JenisSurat  $jenis_surat
	 * @return \Illuminate\Http\Response
	 */
	public function delete(JenisSurat $jenis_surat)
	{
		if (request()->ajax()) {
			$data = [
				'url' => route('jenis-surat.destroy', ['jenis_surat' => $jenis_surat]),
				'data' => $jenis_surat->name
			];
			return response()->json([
				'title' => 'Konfirmasi Hapus',
				'form' => view('delete', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}
	public function destroy(JenisSurat $jenis_surat)
	{
		if (request()->ajax()) {
			if ($jenis_surat->delete()) {
				return response()->json(['message' => 'Data berhasil dihapus']);
			}
			return response()->json(['message' => 'Data gagal dihapus'], 500);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}
}

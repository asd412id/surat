<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Surat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Yajra\DataTables\Facades\DataTables;

class SuratController extends Controller
{
	public function __construct()
	{
		$this->file_validate = [
			'jpg',
			'jpeg',
			'png',
			'pdf',
		];
		$this->max_size = 1000000;
	}

	public function index()
	{
		if (request()->ajax()) {
			$data = Surat::query()
				->with('jenis_surat')
				->with('user')
				->select('surats.*');
			return DataTables::of($data)
				->addColumn('action', function ($row) {

					$btn = '<div class="table-actions">';

					$btn .= ' <a href="#" data-type="modal-lg" data-url="' . route('surat.show', ['surat' => $row]) . '" class="open-modal text-olive m-1" title="Detail"><i class="fas fa-info-circle"></i></a>';

					if ($row->file) {
						$btn .= ' <a href="' . route('surat.download', ['surat' => $row]) . '" class="text-primary m-1" target="_blank" title="Download File"><i class="fas fa-download"></i></a>';
					}

					$btn .= ' <a href="#" data-url="' . route('surat.edit', ['surat' => $row]) . '" class="open-modal text-orange m-1" title="Ubah"><i class="fas fa-edit"></i></a>';

					$btn .= ' <a href="#" data-url="' . route('surat.delete', ['surat' => $row]) . '" class="open-modal text-danger m-1" title="Hapus"><i class="fas fa-trash"></i></a>';

					$btn .= '</div>';

					return $btn;
				})
				->rawColumns(['action'])
				->make(true);
		}
		$data = [
			'title' => 'Surat'
		];
		return view('surat.index', $data);
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
				'url' => route('surat.store'),
				'jenis_surat' => JenisSurat::all(),
			];
			return response()->json([
				'title' => 'Tambah Surat Baru',
				'form' => view('surat.form', $data)->render()
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
				'jenis_surat' => 'required',
				'tanggal' => 'required',
				'nomor' => 'required',
				'perihal' => 'required',
			], [
				'jenis_surat.required' => 'Jenis surat harus dipilih',
				'tanggal.required' => 'Tanggal surat harus diisi',
				'nomor.required' => 'Nomor surat harus diisi',
				'perihal.required' => 'Perihal surat harus diisi',
			]);

			$jenis_surat = JenisSurat::find($request->jenis_surat);
			$nomor = $jenis_surat->kode_depan . $request->nomor . $jenis_surat->kode_belakang;
			$cek = Surat::where('nomor', $nomor)->count();

			if ($cek) {
				return response()->json(['message' => 'Nomor surat sudah digunakan'], 406);
			}

			$file = null;
			$file_type = null;
			if ($request->hasFile('file') && $request->file('file')->isValid()) {
				$file_type = $request->file->extension();
				if (!in_array($file_type, $this->file_validate)) {
					return response()->json(['message' => 'File surat harus berupa file gambar atau pdf'], 406);
				}
				if ($request->file->getSize() > $this->max_size) {
					return response()->json(['message' => 'Ukuran file surat maksimal 1MB'], 406);
				}
				if (!Storage::disk('local')->exists('upload')) {
					Storage::disk('local')->makeDirectory('upload');
				}
				$file = $request->file->store('upload', 'local');
			}

			$insert = new Surat();
			$insert->jenis_surat_id = $request->jenis_surat;
			$insert->tanggal = $request->tanggal;
			if ($jenis_surat->kode_depan || $jenis_surat->kode_belakang) {
				$insert->urutan = $request->nomor;
				$insert->kode_depan = $jenis_surat->kode_depan;
				$insert->kode_belakang = $jenis_surat->kode_belakang;
			}
			$insert->nomor = $nomor;
			$insert->file = $file;
			$insert->file_type = $file_type;
			$insert->perihal = $request->perihal;
			$insert->opt = ['desc' => $request->desc];

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
	 * @param  \App\Models\Surat  $surat
	 * @return \Illuminate\Http\Response
	 */
	public function show(Surat $surat)
	{
		if (request()->ajax()) {
			$data = [
				'data' => $surat,
			];
			return response()->json([
				'title' => 'Detail',
				'form' => view('surat.detail', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Surat  $surat
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Surat $surat)
	{
		if (request()->ajax()) {
			$data = [
				'url' => route('surat.update', ['surat' => $surat]),
				'data' => $surat,
				'jenis_surat' => JenisSurat::all(),
			];
			return response()->json([
				'title' => 'Ubah Data Surat',
				'form' => view('surat.form', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Surat  $surat
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Surat $surat)
	{
		if (request()->ajax()) {
			$request->validate([
				'jenis_surat' => 'required',
				'tanggal' => 'required',
				'nomor' => 'required',
				'perihal' => 'required',
			], [
				'jenis_surat.required' => 'Jenis surat harus dipilih',
				'tanggal.required' => 'Tanggal surat harus diisi',
				'nomor.required' => 'Nomor surat harus diisi',
				'perihal.required' => 'Perihal surat harus diisi',
			]);

			$jenis_surat = JenisSurat::find($request->jenis_surat);
			$nomor = $jenis_surat->kode_depan . $request->nomor . $jenis_surat->kode_belakang;
			$cek = Surat::where('nomor', $nomor)
				->where('uuid', '!=', $surat->uuid)
				->count();

			if ($cek) {
				return response()->json(['message' => 'Nomor surat sudah digunakan'], 406);
			}

			$file = $surat->file;
			$file_type = $surat->file_type;
			if ($request->hasFile('file') && $request->file('file')->isValid()) {
				$file_type = $request->file->extension();
				if (!in_array($file_type, $this->file_validate)) {
					return response()->json(['message' => 'File surat harus berupa file gambar atau pdf'], 406);
				}
				if ($request->file->getSize() > $this->max_size) {
					return response()->json(['message' => 'Ukuran file surat maksimal 1MB'], 406);
				}
				if (!Storage::disk('local')->exists('upload')) {
					Storage::disk('local')->makeDirectory('upload');
				}
				Storage::disk('local')->delete($surat->file);
				$file = $request->file->store('upload', 'local');
			}

			$insert = $surat;
			$insert->jenis_surat_id = $request->jenis_surat;
			$insert->tanggal = $request->tanggal;
			if ($jenis_surat->kode_depan || $jenis_surat->kode_belakang) {
				$insert->urutan = $request->nomor;
				$insert->kode_depan = $jenis_surat->kode_depan;
				$insert->kode_belakang = $jenis_surat->kode_belakang;
			}
			$insert->nomor = $nomor;
			$insert->file = $file;
			$insert->file_type = $file_type;
			$insert->perihal = $request->perihal;
			$insert->opt = ['desc' => $request->desc];

			if ($insert->save()) {
				return response()->json(['message' => 'Data berhasil disimpan']);
			}
			return response()->json(['message' => 'Data gagal disimpan'], 500);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	public function download(Surat $surat)
	{
		if (!$surat->file) {
			return redirect()->route('home')->withErrors('File tidak ditemukan');
		}
		$file = Storage::disk('local')->path($surat->file);
		return response(file_get_contents($file))
			->header('Content-Type', File::mimeType($file))
			->header('Content-disposition', 'filename="' . $surat->nomor . '-' . $surat->perihal . '.' . $surat->file_type . '"');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Surat  $surat
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Surat $surat)
	{
		if (request()->ajax()) {
			$data = [
				'url' => route('surat.destroy', ['surat' => $surat]),
				'data' => $surat->nomor
			];
			return response()->json([
				'title' => 'Konfirmasi Hapus',
				'form' => view('delete', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}
	public function destroy(Surat $surat)
	{
		if (request()->ajax()) {
			Storage::disk('local')->delete($surat->file);
			if ($surat->delete()) {
				return response()->json(['message' => 'Data berhasil dihapus']);
			}
			return response()->json(['message' => 'Data gagal dihapus'], 500);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}
	public function print()
	{
		if (request()->ajax()) {
			$data = [
				'jenis_surat' => JenisSurat::all(),
				'url' => route('surat.print.process')
			];
			return response()->json([
				'title' => 'Cetak Arsip Surat',
				'form' => view('surat.print', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}
	public function printProcess(Request $request)
	{
		$request->validate([
			'jenis_surat' => 'required|array|min:1',
			'tanggal' => 'required'
		], [
			'jenis_surat.required' => 'Jenis surat harus dipilih',
			'jenis_surat.array' => 'Jenis surat tidak dikenal',
			'jenis_surat.min' => 'Jenis surat tidak dikenal',
			'tanggal.required' => 'Rentang tanggal harus dipilih',
		]);

		// try {
		$tgl = explode(' s.d. ', $request->tanggal);
		$start = Carbon::createFromFormat('d-m-Y', $tgl[0]);
		$end = Carbon::createFromFormat('d-m-Y', $tgl[1]);

		$surat = Surat::whereIn('jenis_surat_id', $request->jenis_surat)
			->where('tanggal', '>=', $start->startOfDay())
			->where('tanggal', '<=', $end->endOfDay())
			->with('jenis_surat')
			->orderBy('tanggal', 'asc')
			->get();

		$tgls = $start->toDateString() == $end->toDateString() ? $start->format('d-m-y') : $start->format('d-m-Y') . ' s.d. ' . $end->format('d-m-Y');

		if (count($surat)) {
			$data = [
				'title' => 'Arsip Surat ' . $tgls,
				'start' => $start,
				'end' => $end,
				'jenis_surat' => $request->jenis_surat,
				'data' => $surat,
			];
			$pdf = Pdf::loadView('surat.printview', $data);
			return $pdf->stream($data['title'] . '.pdf');
		}
		return redirect()->back()->withErrors('Tidak ada arsip surat');
		// } catch (\Throwable $th) {
		// 	return redirect()->back()->withErrors('Tidak dapat memproses data');
		// }
	}
}

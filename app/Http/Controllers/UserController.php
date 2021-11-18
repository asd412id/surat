<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function login()
	{
		return view('login', [
			'title' => 'Halaman Login'
		]);
	}

	public function loginProcess(Request $r)
	{
		$r->validate([
			'username' => 'required',
			'password' => 'required'
		], [
			'username.required' => 'Username tidak boleh kosong',
			'password.required' => 'Password tidak boleh kosong',
		]);

		if (Auth::attempt(['username' => $r->username, 'password' => $r->password], $r->remember ? true : false)) {
			return redirect()->back()->with('message', 'Login berhasil');
		}
		return redirect()->back()->withErrors('Username dan password ditolak');
	}
	public function home()
	{
		return view('home', [
			'title' => 'Beranda'
		]);
	}

	public function logout()
	{
		if (request()->ajax()) {
			return response()->json([
				'title' => 'Konfirmasi',
				'form' => view('logout')->render()
			]);
		}
		return redirect()->route('home')->withErrors('Anda tidak memiliki akses!');
	}
	public function logoutProcess()
	{
		if (request()->ajax()) {
			$user = auth()->user();
			$user->_token = null;
			$user->save();
			auth()->logout();
			return response()->json(['message' => 'Anda berhasil keluar!<br>Mengarahkan ke halaman login ...', 'redirect' => route('login'), 'timeout' => 500]);
		}
		return redirect()->route('home')->withErrors('Anda tidak memiliki akses!');
	}
	public function account()
	{
		if (request()->ajax()) {
			$data = [
				'title' => auth()->user()->role == 0 ? 'Pengaturan Akun' : 'Ubah Password',
				'user' => auth()->user()
			];
			return response()->json([
				'title' => $data['title'],
				'form' => view('account', $data)->render(),
			]);
		}
		return redirect()->route('home')->withErrors('Anda tidak memiliki akses!');
	}
	public function accountUpdate(Request $r)
	{
		if ($r->ajax()) {
			$rules = [
				'name' => 'required',
				'username' => 'required',
				'password' => 'required',
				'newpassword' => 'confirmed',
			];

			$msgs = [
				'name.required' => 'Nama tidak boleh kosong!',
				'username.required' => 'Username tidak boleh kosong!',
				'password.required' => 'Password tidak boleh kosong!',
				'newpassword.confirmed' => 'Perulangan password tidak benar!',
			];

			if (auth()->user()->role != 0) {
				unset($rules['name']);
				unset($rules['username']);
				unset($msgs['name.required']);
				unset($msgs['username.required']);
			}

			$r->validate($rules, $msgs);

			$user = auth()->user();

			if (!Hash::check($r->password, $user->password)) {
				return response()->json(['message' => 'Password tidak benar!'], 406);
			}

			if (auth()->user()->role == 0) {
				$user->name = $r->name;
				$user->username = $r->username;
			}
			if ($r->newpassword) {
				$user->password = bcrypt($r->newpassword);
			}

			if ($user->save()) {
				return response()->json(['message' => 'Data akun berhasil diubah'], 202);
			}
			return response()->json(['message' => 'Tidak dapat mengubah data akun!'], 500);
		}
		return redirect()->route('home')->withErrors('Anda tidak memiliki akses!');
	}

	public function index()
	{
		if (request()->ajax()) {
			$data = User::query()
				->where('uuid', '!=', auth()->user()->uuid);
			return DataTables::of($data)
				->addColumn('action', function ($row) {

					$btn = '<div class="table-actions">';

					$btn .= ' <a href="#" data-url="' . route('user.show', ['user' => $row]) . '" class="open-modal text-olive m-1" title="Detail"><i class="fas fa-info-circle"></i></a>';

					$btn .= ' <a href="#" data-url="' . route('user.edit', ['user' => $row]) . '" class="open-modal text-orange m-1" title="Ubah"><i class="fas fa-edit"></i></a>';

					$btn .= ' <a href="#" data-url="' . route('user.delete', ['user' => $row]) . '" class="open-modal text-danger m-1" title="Hapus"><i class="fas fa-trash"></i></a>';

					$btn .= '</div>';

					return $btn;
				})
				->rawColumns(['action'])
				->make(true);
		}
		$data = [
			'title' => 'User'
		];
		return view('user.index', $data);
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
				'url' => route('user.store'),
			];
			return response()->json([
				'title' => 'Tambah User Baru',
				'form' => view('user.form', $data)->render()
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
				'name' => 'required',
				'username' => 'required|unique:users,username',
				'password' => 'required',
			], [
				'name.required' => 'Nama user harus diisi',
				'username.required' => 'Username harus diisi',
				'username.unique' => 'Username telah digunakan',
				'password.required' => 'Password harus diisi',
			]);

			$insert = new User();
			$insert->name = $request->name;
			$insert->username = $request->username;
			$insert->password = $request->password ? bcrypt($request->password) : bcrypt($request->username);
			$insert->jenis_kelamin = $request->jenis_kelamin;
			$insert->role = $request->role;

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
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function show(User $user)
	{
		if (request()->ajax()) {
			$data = [
				'data' => $user,
			];
			return response()->json([
				'title' => 'Detail',
				'form' => view('user.detail', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function edit(User $user)
	{
		if (request()->ajax()) {
			$data = [
				'url' => route('user.update', ['user' => $user]),
				'data' => $user,
			];
			return response()->json([
				'title' => 'Ubah Data User',
				'form' => view('user.form', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, User $user)
	{
		if (request()->ajax()) {
			$request->validate([
				'name' => 'required',
				'username' => 'required|unique:users,username,' . $user->uuid . ',uuid',
			], [
				'name.required' => 'Nama user harus diisi',
				'username.required' => 'Username harus diisi',
				'username.unique' => 'Username telah digunakan'
			]);

			$user->name = $request->name;
			$user->username = $request->username;
			$user->jenis_kelamin = $request->jenis_kelamin;
			$user->role = $request->role;
			if ($request->password) {
				$user->password = bcrypt($request->password);
			}

			if ($user->save()) {
				return response()->json(['message' => 'Data berhasil disimpan']);
			}
			return response()->json(['message' => 'Data gagal disimpan'], 500);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function delete(User $user)
	{
		if (request()->ajax()) {
			$data = [
				'url' => route('user.destroy', ['user' => $user]),
				'data' => $user->name
			];
			return response()->json([
				'title' => 'Konfirmasi Hapus',
				'form' => view('delete', $data)->render()
			]);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}
	public function destroy(User $user)
	{
		if (request()->ajax()) {
			if ($user->delete()) {
				return response()->json(['message' => 'Data berhasil dihapus']);
			}
			return response()->json(['message' => 'Data gagal dihapus'], 500);
		}
		return redirect()->route('home')->withErrors('Akses ditolak!');
	}
}

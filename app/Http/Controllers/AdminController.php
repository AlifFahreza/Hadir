<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Presensi;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function getdata()
    {
        if (!session('id')) {
            return redirect()->route('login');
        }

        return view('registeradmin');
    }

    public function register(Request $request)
    {

        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin',
            'password' => 'required|string',
            'institusi' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'more' => 'required|string|max:255',

        ]);

        // Membuat admin baru
        $admin = Admin::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'institusi' => $validatedData['institusi'],
            'departemen' => $validatedData['departemen'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'more' => $validatedData['more'],
            'foto' => 'assets/img/profile-img.jpg',
        ]);

        // Redirect ke halaman yang sesuai setelah pendaftaran berhasil
        return redirect('/');
    }

    public function update(Request $request)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
            'institusi' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'more' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Jika ingin membatasi ukuran gambar
        ]);

        // Cari data admin berdasarkan ID
        $admin = Admin::find($request->userId);

        // Jika admin tidak ditemukan, kembalikan response error
        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found.');
        }

        // Update data admin dengan data yang diterima dari form
        $admin->name = $validatedData['name'];
        $admin->departemen = $validatedData['departemen'];
        $admin->institusi = $validatedData['institusi'];
        $admin->email = $validatedData['email'];
        $admin->address = $validatedData['address'];
        $admin->phone = $validatedData['phone'];
        $admin->more = $validatedData['more'];

        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalName();
            $filePath = 'assets/img';
            $file->move(public_path($filePath), $fileName); // Move the file to the specified directory
            $admin->foto = "assets/img/" . $fileName; // Set the filename to the admin model

            // Perbarui data foto di session
            session()->put('foto', $admin->foto);
        }

        // Simpan perubahan data admin
        $admin->save();

        // Redirect ke halaman yang sesuai setelah update berhasil
        return back()->with('successs', 'Profile admin has been updated successfully.');
    }


    public function updatepass(Request $request)
{
    if (!session('id')) {
        // Redirect to login page if admin id is empty
        return redirect()->route('login');
    }
    // dd("aa");
    // Validasi data yang diterima dari form
    $validatedData = $request->validate([
        'currentPassword' => 'required',
        'newPassword' => 'required|string|confirmed',
    ]);

    $id = session('id');

    // Mendapatkan user yang sedang login
    $admin = Admin::where('id', $id)->first();
    // dd($admin);

    // Memeriksa apakah password lama yang dimasukkan benar
    if (!password_verify($validatedData['currentPassword'], $admin->password)) {
        return back()->with('error', 'Password lama yang Anda masukkan salah.');
    }

    // Update password user dengan password baru
    $admin->password = bcrypt($validatedData['newPassword']);
    $admin->save();

    // Redirect dengan pesan sukses
    return back()->with('success', 'Password berhasil diperbarui.');
}


}

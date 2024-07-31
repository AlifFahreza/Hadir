<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Presensi;
use App\Models\Anggota;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class AnggotaController extends Controller
{
    use  HasFactory, Notifiable, CanResetPassword;   

    public function getdata()
    {
        if (!session('id')) {
            return redirect()->route('login');
        }

        $adminId = session('id');
        $users = Anggota::all();
        $adminId = session('id');

        // Update presensi status to 'Selesai' for presensi with 'time_end' in the past
        Presensi::where('id_admin', $adminId)
            ->where('status', 'Belum')
            ->where('time_end', '<', Carbon::now())
            ->update(['status' => 'Selesai']);

        // Count the number of presensi with status 'Belum' for the admin
        $belumCount = Presensi::where('id_admin', $adminId)
            ->where('status', 'Belum')
            ->count();

        // Count the number of presensi with status 'Selesai' for the admin
        $selesaiCount = Presensi::where('id_admin', $adminId)
            ->where('status', 'Selesai')
            ->count();

        // Count the total number of presensi for the admin
        $totalPresensi = Presensi::where('id_admin', $adminId)->count();

        // Calculate the percentage of presensi 'Belum' from total
        $belumPercentage = $totalPresensi > 0 ? ($belumCount / $totalPresensi) * 100 : 0;

        // Calculate the percentage of presensi 'Selesai' from total
        $selesaiPercentage = $totalPresensi > 0 ? ($selesaiCount / $totalPresensi) * 100 : 0;

        // Retrieve all presensi data for the admin with status 'Belum'
        $presensiData = Presensi::where('id_admin', $adminId)
            ->where('status', 'Belum')
            ->orderBy('id', 'desc')
            ->get();


        return view('userdata', compact('users', 'presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage'));
    }

    public function updateProfile(Request $request)
    {

        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'id' => 'required', // Tambahkan validasi untuk bidang ID
            'name' => 'required',
            'institusi' => 'required',
            'departemen' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->id, // Menggunakan $request->id
            'foto' => 'nullable|image|max:2048',
            'password' => 'nullable', // Password menjadi opsional
        ]);

        // Ambil data pengguna berdasarkan ID
        $user = Anggota::findOrFail($request->id);

        // Perbarui data pengguna
        $user->name = $validatedData['name'];
        $user->institusi = $validatedData['institusi'];
        $user->departemen = $validatedData['departemen'];
        $user->address = $validatedData['address'];
        $user->phone = $validatedData['phone'];
        $user->email = $validatedData['email'];

        // Periksa apakah password baru dimasukkan
        if ($request->filled('password')) {
            $user->password = bcrypt($validatedData['password']);
        }

        // Periksa apakah ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            // Ambil nama file foto
            $fotoName = $request->file('foto')->getClientOriginalName();
            // Simpan file foto ke penyimpanan
            $request->file('foto')->storeAs('public/foto', $fotoName);
            // Simpan nama file foto ke dalam data pengguna
            $user->foto = $fotoName;
        }

        // Simpan perubahan
        $user->save();

        // Redirect ke halaman home dengan pesan sukses
        return redirect('/userdata')->with('success', 'Profil berhasil diperbarui.');
    }


    public function deleteProfile($id)
    {
        // Temukan data pengguna berdasarkan ID
        $user = Anggota::find($id);
// dd($id);
        // Periksa apakah data pengguna ditemukan
        if (!$user) {
            // Jika tidak ditemukan, kembalikan response dengan pesan error
            return redirect()->back()->with('error', 'Data pengguna tidak ditemukan.');
        }

        // Hapus data pengguna
        $user->delete();

        // Redirect ke halaman yang sesuai dengan pesan sukses
        return redirect('/userdata')->with('success', 'Data pengguna berhasil dihapus.');
    }

    public function register(Request $request)
    {

        // dd("aaa");
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:anggota',
            'password' => 'required',
            'institusi' => 'required|string|max:80',
            'departemen' => 'required|string|max:50',
            'address' => 'required|string|max:200',
            'phone' => 'required|string|max:16',
        ]);


        // Membuat pengguna baru
        $user = Anggota::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'institusi' => $validatedData['institusi'],
            'departemen' => $validatedData['departemen'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'foto' => 'assets/img/profile-img.jpg',
        ]);


        Alert::success('Success', 'Registration successful!');
        // Redirect ke halaman yang sesuai setelah pendaftaran berhasil
        return redirect('/');;
    }

    public function create()
    {
        return view('login'); // Ganti 'login' dengan nama view login Anda
    }
    public function store()
    {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        // Check if the user exists in the users table
        $user = Anggota::where('email', $credentials['email'])->first();
        if ($user && password_verify($credentials['password'], $user->password)) {
            // If the user exists and the password is correct, log in the user
            session(['name' => $user->name]);
            session(['id' => $user->id]);
            session(['foto' => $user->foto ?: 'https://assets.materialup.com/uploads/b6c33467-82c3-442c-a2dc-c089bbff9fa1/preview.png']);
            session(['level' => "user"]);
            return redirect('/user');
        }
        // dd($user);

        // Check if the user exists in the admins table
        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin && password_verify($credentials['password'], $admin->password)) {
            session(['name' => $admin->name]);
            session(['id' => $admin->id]);
            session(['foto' => $admin->foto ?: 'https://assets.materialup.com/uploads/b6c33467-82c3-442c-a2dc-c089bbff9fa1/preview.png']);
            session(['level' => "admin"]);
            return redirect('/dashboardAdmin'); // Redirect ke halaman dashboard admin
        }

// dd(bcrypt($credentials['password']));
        if ($admin && password_verify($credentials['password'], $admin->password)) {
            // If the admin exists and the password is correct, log in the admin
            session(['name' => $admin->name]);
            session(['id' => $admin->id]);
            session(['foto' => $admin->foto ?: 'https://assets.materialup.com/uploads/b6c33467-82c3-442c-a2dc-c089bbff9fa1/preview.png']);
            session(['level' => "admin"]);
            // dd( password_verify($credentials['password'], $admin->password));

            return redirect()->route('admin.dashboard');
        }


        // If no user or admin with the provided email and password is found
        return redirect('/sign-in')->with('error', 'Invalid email or password.');
    }


    public function logout(Request $request)
    {
        // Hapus semua data dari session
        $request->session()->flush();

        // Redirect ke halaman login
        return redirect('/');
    }

    public function redirectToProfile()
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        $level = session('level'); // Mendapatkan level dari session

        // Lakukan pengecekan level
        if ($level === 'user') {
            // Jika level adalah user, temukan data user dan redirect ke halaman profile user
            $user = Anggota::find(session('id'));
            if ($user) {
                return view('profileuser', compact('user'));
            } else {
                return redirect('/login')->with('error', 'User not found.');
            }
        } elseif ($level === 'admin') {
            // Jika level adalah admin, temukan data admin dan redirect ke halaman profile admin
            $admin = Admin::find(session('id'));
            if ($admin) {
                return view('profileadmin', compact('admin'));
            } else {
                return redirect('/login')->with('error', 'Admin not found.');
            }
        } else {
            // Jika level tidak sesuai, redirect ke halaman login atau halaman lainnya
            return redirect('/login')->with('error', 'Invalid user level.');
        }
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

            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Jika ingin membatasi ukuran gambar
        ]);

        // Cari data admin berdasarkan ID
        $user = Anggota::find($request->userId);

        // Jika admin tidak ditemukan, kembalikan response error
        if (!$user) {
            return redirect()->back()->with('error', 'Admin not found.');
        }

        // Update data admin dengan data yang diterima dari form
        $user->name = $validatedData['name'];
        $user->departemen = $validatedData['departemen'];
        $user->institusi = $validatedData['institusi'];
        $user->email = $validatedData['email'];
        $user->address = $validatedData['address'];
        $user->phone = $validatedData['phone'];


        // Proses upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalName();
            $filePath = 'assets/img';
            $file->move(public_path($filePath), $fileName); // Move the file to the specified directory
            $user->foto = "assets/img/" . $fileName; // Set the filename to the admin model

            // Perbarui data foto di session
            session()->put('foto', $user->foto);
        }

        // Simpan perubahan data admin
        $user->save();

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
        $user = Anggota::where('id', $id)->first();
        // dd($user);

        // Memeriksa apakah password lama yang dimasukkan benar
        if (!password_verify($validatedData['currentPassword'], $user->password)) {
            return back()->with('error', 'Password lama yang Anda masukkan salah.');
        }

        // Update password user dengan password baru
        $user->password = bcrypt($validatedData['newPassword']);
        $user->save();

        // Redirect dengan pesan sukses
        return back()->with('success', 'Password berhasil diperbarui.');
    }
}

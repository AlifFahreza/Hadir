<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use App\Models\Presensi;
use Carbon\Carbon;
use PDF; // Import alias PDF
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class PresensiController extends Controller
{
    public function insertPresensi(Request $request)
    {

        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        
        $validatedData = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'time_start' => 'required',
            'time_end' => 'required',
            'id_organisasi' => 'required', // Tambahkan validasi untuk id_organisasi
        ]);

        $kodeAcak = Str::random(10);
        $adminId = session('id');

        Presensi::create([
            'kode_acak' => $kodeAcak,
            'id_admin' => $adminId,
            'event_name' => $validatedData['event_name'],
            'description' => $validatedData['description'],
            'time_start' => $validatedData['time_start'],
            'time_end' => $validatedData['time_end'],
            'status' => "Belum",
            'id_organisasi' => $validatedData['id_organisasi'], // Tambahkan id_organisasi
        ]);

        $success = true;
        return redirect('/admin')->with('success', $success)->with('kodeAcak', $kodeAcak);

    }


    public function updatePresensi(Request $request, $id)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Validasi data yang diterima dari form
        $validatedData = $request->validate([
            'event_name' => 'required|string|max:255',
            'description' => 'required|string',
            'time_start' => 'required',
            'time_end' => 'required',
            'status' => 'required',
            'id_organisasi' => 'required', // Tambahkan validasi untuk id_organisasi
        ]);

        // Mendapatkan id_admin dari sesi
        $adminId = session('id');

        // Perbarui data presensi berdasarkan ID, id_admin, dan id_organisasi
        Presensi::where('id', $id)
                ->where('id_admin', $adminId)
                ->update([
                    'event_name' => $validatedData['event_name'],
                    'description' => $validatedData['description'],
                    'time_start' => $validatedData['time_start'],
                    'time_end' => $validatedData['time_end'],
                    'status' => $validatedData['status'],
                    'id_organisasi' => $validatedData['id_organisasi'], // Perbarui id_organisasi
                ]);
        $update = true;

        // Redirect back to the previous page
        return redirect()->back()->with('update', $update);
    }

public function deletePresensi($id)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Cari data presensi berdasarkan ID
        $presensi = Presensi::find($id);

        // Periksa apakah data presensi ditemukan
        if (!$presensi) {
            // Jika tidak ditemukan, redirect dengan pesan error
            return redirect()->back()->with('error', 'Data presensi tidak ditemukan.');
        }
        $delete= true;

        // Hapus data presensi
        $presensi->delete();

        // Redirect kembali ke halaman admin dashboard dengan pesan sukses
        return redirect()->back()->with('delete', $delete);
    }

    public function getdata()
    {
        // Check if admin id exists in session
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }

        // Get id_admin from session
        $adminId = session('id');

        // Update presensi status to 'Selesai' for presensi with 'time_end' in the past
        Presensi::where('id_admin', $adminId)
                ->where('status', 'Belum')
                ->where('time_end', '<', Carbon::now())
                ->get();


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
        $organisasi = Organisasi::all();

        // Calculate the percentage of presensi 'Belum' from total
        $belumPercentage = $totalPresensi > 0 ? ($belumCount / $totalPresensi) * 100 : 0;

        // Calculate the percentage of presensi 'Selesai' from total
        $selesaiPercentage = $totalPresensi > 0 ? ($selesaiCount / $totalPresensi) * 100 : 0;

        // Retrieve all presensi data for the admin with status 'Belum'
        $presensiData = Presensi::join('organisasi', 'presensi.id_organisasi', '=', 'organisasi.id')
        ->where('presensi.id_admin', $adminId)
        ->where('presensi.status', 'Belum')
        ->orderBy('presensi.id', 'desc')
        ->select('presensi.*', 'organisasi.nama as nama_organisasi')
        ->get();


        // Return the presensi data along with counts and percentages
        return view('adminDashboard', compact('organisasi','presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage'));
    }

    public function getOrganisasi()
    {
        if (!session('id')) {
            return redirect()->route('login');
        }

        $adminId = session('id');
        $user = Organisasi::all();

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

        return view('organisasidata', compact('user', 'presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage'));
    }


    public function getdatahistory()
    {
        // Check if admin id exists in session
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect('/login');
        }

        // Get id_admin from session
        $adminId = session('id');

        // Update presensi status to 'Selesai' for presensi with 'time_end' in the past
        Presensi::where('id_admin', $adminId)
                ->where('status', 'Belum')
                ->where('time_end', '<', Carbon::now())
                ->update(['status' => 'Selesai']);
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
        // Retrieve all presensi data for the admin
        $presensiData = Presensi::where('id_admin', $adminId) ->where('status', 'Selesai')
                                ->orderBy('id', 'desc')
                                ->get();

        $riwayatjadwal = Organisasi::all();
        // Return the presensi data
        return view('riwayat', compact('riwayatjadwal','presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage'));
    }

    public function cetak(Request $request)
    {
        $organisasi_id = $request->input('organisasi_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validasi input
        $request->validate([
            'organisasi_id' => 'required|exists:organisasi,id',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        // Check if admin id exists in session
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect('/login');
        }

        // Get id_admin from session
        $adminId = session('id');

        // Update presensi status to 'Selesai' for presensi with 'time_end' in the past
        Presensi::where('id_admin', $adminId)
                ->where('status', 'Belum')
                ->where('time_end', '<', Carbon::now())
                ->update(['status' => 'Selesai']);
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
            // Retrieve all presensi data for the admin
            $presensiData = Presensi::where('id_admin', $adminId) ->where('status', 'Selesai')
                                    ->orderBy('id', 'desc')
                                    ->get();

        // Ambil nama organisasi
        $organisasi = Organisasi::find($organisasi_id);
        $riwayatjadwal = Organisasi::all();

        // Ubah format tanggal jika diperlukan, misal dari 'd/m/Y' ke 'Y-m-d'
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        // Query untuk mengambil kode_acak dari presensi
        $kode_acak = DB::table('sekretaris')
        ->join('presensi', 'sekretaris.id_presensi', '=', 'presensi.id')
        ->select('presensi.kode_acak', 'presensi.id')
        ->where('presensi.id_organisasi', $organisasi_id)
        ->whereDate('presensi.time_start', '<=', $startDate)
        ->whereDate('presensi.time_end', '>=', $endDate)
        ->first(); // Mengambil hanya satu baris pertama

        // Jika kode_acak tidak ditemukan, tampilkan pesan bahwa data tidak ditemukan
        if (!$kode_acak) {
            $data = []; // Atau $data = null; tergantung bagaimana Anda menangani data di view

            // Redirect atau tampilkan view dengan pesan bahwa data tidak ditemukan
            return view('riwayat', compact('data','riwayatjadwal','presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage'))->with('warning', true);
        }

        // Mengambil data sekretaris berdasarkan kode_acak yang ditemukan
        $data = DB::table('sekretaris')
        ->join('anggota', 'sekretaris.id_anggota', '=', 'anggota.id')
        ->select('anggota.name', 'anggota.institusi', 'anggota.departemen', 'anggota.phone', 'sekretaris.created_at as waktu_presensi')
        ->where('sekretaris.id_presensi', $kode_acak->id) // Menggunakan id_presensi dari $kode_acak
        ->get();

        if ($data->isEmpty()) {
            return view('riwayat', compact('data','riwayatjadwal','presensiData', 'belumCount', 'selesaiCount', 'totalPresensi', 'belumPercentage', 'selesaiPercentage'))->with('warning', true);
        }

        // Query to count absent members
        $countAbsen = DB::table('sekretaris')
            ->join('presensi', 'sekretaris.id_presensi', '=', 'presensi.id')
            ->select('presensi.kode_acak', 'presensi.id')
            ->where('presensi.id_organisasi', $organisasi_id)
            ->whereDate('presensi.time_start', '<=', $startDate)
            ->whereDate('presensi.time_end', '>=', $endDate)
            ->count();

            return view('cetakanggota', compact('organisasi', 'countAbsen', 'kode_acak', 'data'));
            
    }
}

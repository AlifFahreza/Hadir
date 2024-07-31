<?php

namespace App\Http\Controllers;

use App\Models\Sekretaris;
use App\Models\Presensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SekretarisController extends Controller
{

    public function getAllDetailPresensi()
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Dapatkan id_anggota dari session
        $userId = session('id'); // Sesuaikan dengan key sesi yang Anda gunakan
        
        // Ambil semua data detail presensi yang terkait dengan id_anggota dari session
        $dataPresensi = Presensi::whereHas('sekretaris', function($query) use ($userId) {
            $query->where('id_anggota', $userId); // Filter berdasarkan id_anggota yang sesuai dengan userId
        })
        ->with(['sekretaris' => function($query) {
            $query->where('id_anggota', session('id')) // Filter berdasarkan session id
                  ->select('id_presensi', 
                           DB::raw('DATE(created_at) as tanggal_presensi'), 
                           DB::raw('TIME(created_at) as jam_presensi'));
        }])
        ->join('organisasi', 'presensi.id_organisasi', '=', 'organisasi.id')
        ->select('presensi.id', 
                 'presensi.kode_acak', 
                 'presensi.event_name', 
                 'presensi.description', 
                 'presensi.time_start', 
                 'presensi.time_end',
                 'organisasi.nama as nama_organisasi')
        ->get();
    
        return view('dashboard', ['dataPresensi' => $dataPresensi]);
    }

    public function getDetailPresensi($idPresensi)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Get detail presensi berdasarkan id presensi
        $detailPresensi = Sekretaris::where('id_presensi', $idPresensi)
            ->join('presensi', 'sekretaris.id_presensi', '=', 'presensi.id')
            ->join('anggota', 'sekretaris.id_anggota', '=', 'anggota.id')
            ->select('sekretaris.*','sekretaris.updated_at as absen','sekretaris.id as id_detail', 'presensi.*', 'anggota.*')
            ->get();

            $totalPresensi = Sekretaris::where('id_presensi', $idPresensi)->count();

        if ($detailPresensi->isEmpty()) {
            $kosong = true;
            return redirect()->back()->with('kosong', $kosong);
        }

        // Jika berhasil, kembalikan data detail presensi
        return view('detailpresensi', compact('detailPresensi', 'totalPresensi'));
    }
    public function deleteDetailPresensi($id)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Cari data detail presensi berdasarkan ID
        $detailPresensi = Sekretaris::find($id);

        // Jika data tidak ditemukan, kembalikan response error
        if (!$detailPresensi) {
            return redirect()->back()->with('error', 'Detail Presensi tidak ditemukan.');
        }

        // Hapus data detail presensi
        $detailPresensi->delete();

        // Periksa apakah setelah penghapusan data, jumlah data detail presensi menjadi kosong
        $totalDetailPresensi = Sekretaris::count();
        if ($totalDetailPresensi === 0) {
            $detail = true;
            // Jika kosong, arahkan ke route /admin
            return redirect('/admin')->with('detail', $detail);
        }


        // Jika tidak kosong, kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Detail Presensi berhasil dihapus.');
    }



    public function store($kode_acak)
    {
        if (!session('id')) {
            // Redirect to login page if admin id is empty
            return redirect()->route('login');
        }
        // Dapatkan presensi berdasarkan kode_acak yang diinputkan oleh pengguna
        $presensi = Presensi::where('kode_acak', $kode_acak)->first();

        // Pastikan presensi ditemukan
        if (!$presensi) {
            $error = true;
            return redirect()->back()->with('error', $error);
        }

        // Periksa status presensi
        if ($presensi->status == 'Selesai') {
            $gagal = true;
            return redirect()->back()->with('gagal', $gagal);
        }

        // Dapatkan useid_anggotar_id dari session
        $userId = session('id'); // Sesuaikan dengan key sesi yang Anda gunakan

        // Periksa apakah user sudah presensi
        $userPresensi = Sekretaris::where('id_presensi', $presensi->id)
            ->where('id_anggota', $userId)
            ->exists();

        // Jika user sudah presensi, beri tahu pengguna
        if ($userPresensi) {
            $done = true;
            return redirect()->back()->with('done', $done);
        }

        // Buat instance baru dari model DetailPresensi
        $detailPresensi = new Sekretaris();
        $detailPresensi->id_presensi = $presensi->id;
        $detailPresensi->id_anggota = $userId;

        // Simpan data DetailPresensi ke dalam database
        $detailPresensi->save();
        $success = true;
        // Redirect atau lakukan tindakan lain setelah berhasil menyimpan
        return redirect()->back()->with('success', $success);
    }
}

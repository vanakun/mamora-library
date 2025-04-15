<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Kategori;

class DashboardAdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'jumlahUser' => User::role('user')->count(),
            'jumlahBuku' => Buku::count(),
            'totalKategoris' => Kategori::count(),
            'jumlahPeminjaman' => Peminjaman::where('status', 'dikembalikan')->count(),
        ]);
    }

    public function getChartData()
    {
        // Data jumlah peminjaman per bulan
        $dataPeminjaman = DB::table('peminjamans')
            ->selectRaw('EXTRACT(MONTH FROM tanggal_pinjam) as bulan, COUNT(*) as total')
            ->groupByRaw('EXTRACT(MONTH FROM tanggal_pinjam)')
            ->orderByRaw('EXTRACT(MONTH FROM tanggal_pinjam)')
            ->get();
    
        $labels = $dataPeminjaman->map(fn($d) => Carbon::create()->month($d->bulan)->format('F'));
        $values = $dataPeminjaman->pluck('total');
    
        // Data jumlah buku per kategori
        $dataBukuPerKategori = DB::table('bukus')
            ->join('kategoris', 'bukus.kategori_id', '=', 'kategoris.id')
            ->select('kategoris.nama as kategori', DB::raw('SUM(bukus.stok) as total_buku'))
            ->groupBy('kategoris.nama')
            ->get();
    
        $kategoriLabels = $dataBukuPerKategori->pluck('kategori');
        $bukuValues = $dataBukuPerKategori->pluck('total_buku');
    
        // Data peminjaman berdasarkan buku_id, tampilkan judul buku
        $dataPeminjamanBuku = DB::table('peminjamans')
            ->join('bukus', 'peminjamans.buku_id', '=', 'bukus.id')
            ->select('bukus.judul', DB::raw('COUNT(*) as total_borrowed'))
            ->groupBy('bukus.judul')
            ->orderByDesc('total_borrowed')
            ->get();
    
        $bukuLabels = $dataPeminjamanBuku->pluck('judul');  //judul
        $peminjamanValues = $dataPeminjamanBuku->pluck('total_borrowed');
    
        

        // Mengembalikan data dalam format JSON
        return response()->json([
            'labels' => $labels,
            'values' => $values,
            'kategoriLabels' => $kategoriLabels,
            'bukuValues' => $bukuValues,
            'bukuLabels' => $bukuLabels,  
            'peminjamanValues' => $peminjamanValues,            
        ]);
    }
    

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class PerpustakaanController extends Controller
{
    // Menampilkan daftar buku yang tersedia untuk dipinjam
    public function index()
{
    // Mengambil buku yang memiliki stok lebih dari 0
    $bukus = Buku::where('stok', '>', 0)->paginate(6);

    // Mengambil peminjaman berdasarkan user yang login
    $peminjaman = Peminjaman::where('user_id', auth()->id())->get();

    return view('user.index', compact('bukus', 'peminjaman'));
}



// app/Http/Controllers/PeminjamanController.php

public function data()
{
    $user = Auth::user();

    $peminjaman = Peminjaman::with('buku')
        ->where('user_id', $user->id)
        ->get();

    return datatables()->of($peminjaman)
        ->addColumn('tanggal_pinjam', function($pinjam) {
            return \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d-m-Y');
        })
        ->addColumn('tanggal_kembali', function($pinjam) {
            return $pinjam->tanggal_kembali 
                ? \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d-m-Y') 
                : 'Belum Kembali';
        })
        ->addColumn('status', function($pinjam) {
            $statusHtml = '';
            switch (strtolower($pinjam->status)) {
                case 'dipinjam':
                    $statusHtml = '<span class="px-2 py-1 bg-yellow-200 text-yellow-800 text-sm rounded-full">Dipinjam</span>';
                    $statusHtml .= ' <a href="' . route('peminjaman.updateStatus', $pinjam->id) . '" class="text-blue-600 font-semibold hover:underline">Kembalikan</a>';
                    break;
                case 'dikembalikan':
                    $statusHtml = '<span class="px-2 py-1 bg-green-200 text-green-800 text-sm rounded-full">Dikembalikan</span>';
                    break;
                case 'menunggu admin':
                    $statusHtml = '<span class="px-2 py-1 bg-red-200 text-red-800 text-sm rounded-full">Menunggu Dikembalikan</span>';
                    break;
                default:
                    $statusHtml = '<span class="px-2 py-1 bg-gray-200 text-gray-800 text-sm rounded-full">' . ucfirst($pinjam->status) . '</span>';
            }
            return $statusHtml;
        })
        ->rawColumns(['status']) // penting agar HTML-nya bisa dirender
        ->make(true);
}

public function updateStatus($id)
{
    $peminjaman = Peminjaman::findOrFail($id);
    
    // Cek status dan ubah menjadi status berikutnya
    if ($peminjaman->status === 'dipinjam') {
        $peminjaman->status = 'menunggu_admin';  // Ubah ke "Dikembalikan"
    } elseif ($peminjaman->status === 'terlambat') {
        $peminjaman->status = 'dikembalikan';  // Ubah ke "Dikembalikan"
    }

    $peminjaman->save();

    return redirect()->route('user.dashboard')->with('success', 'Status peminjaman telah diubah.');
}
    

    public function pinjam($id)
    {
        $buku = Buku::findOrFail($id);
    
        if ($buku->stok < 1) {
            return back()->with('error', 'Stok buku tidak tersedia.');
        }
    
        // Hitung nomor urut berdasarkan bulan dan tahun ini
        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');
        $count = Peminjaman::whereMonth('created_at', $bulan)
                           ->whereYear('created_at', $tahun)
                           ->count() + 1;
    
        // Format kode: 0001/04/2025
        $kode_pinjam = str_pad($count, 4, '0', STR_PAD_LEFT) . '/' . $bulan . '/' . $tahun;
    
        // Simpan data peminjaman
        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $buku->id,
            'kode_pinjam' => $kode_pinjam,
            'tanggal_pinjam' => Carbon::now(),
            'tanggal_kembali' => Carbon::now()->addDays(10),
            'status' => 'dipinjam',
        ]);
    
        // Kurangi stok buku
        $buku->decrement('stok');
    
        return back()->with('success', 'Buku berhasil dipinjam.');
    }
    public function ubahStatus($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
    
        if ($peminjaman->user_id !== auth()->id()) {
            return back()->with('error', 'Anda tidak berhak mengubah status ini.');
        }
    
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Status hanya bisa diubah dari "Dipinjam".');
        }
    
        $peminjaman->status = 'menunggu dikembalikan';
        $peminjaman->save();
    
        return back()->with('success', 'Status berhasil diubah menjadi Menunggu Dikembalikan.');
    }
    public function ShowMore()
{
    
    return view('user.showmore');
}

public function getBukuList(Request $request)
{
    if ($request->ajax()) {
        $data = Buku::with('kategori')->where('stok', '>', 0)->get(); // stok > 0

        return DataTables::of($data)
            ->addIndexColumn() // No urut otomatis

            ->addColumn('kategori', function ($row) {
                return $row->kategori ? $row->kategori->nama : '-';
            })

            ->addColumn('gambar', function ($row) {
                if ($row->gambar) {
                    $url = asset('storage/gambar_buku/' . $row->gambar);
                    return '<img src="' . $url . '" class="w-16 h-20 object-cover rounded shadow">';
                }
                return '<span class="text-gray-400 italic">Tidak ada</span>';
            })

            ->addColumn('action', function ($row) {
                $form = '
                    <form action="' . route('peminjaman.pinjam', $row->id) . '" method="POST">
                        ' . csrf_field() . '
                        <button type="submit" 
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded shadow transition duration-300">
                            📖 Pinjam
                        </button>
                    </form>
                ';
                return $form;
            })

            ->rawColumns(['gambar', 'action'])
            ->make(true);
    }
}






    // Menampilkan daftar buku yang sudah dipinjam user
    public function riwayat()
    {
        $peminjamans = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('peminjaman.riwayat', compact('peminjamans'));
    }
}

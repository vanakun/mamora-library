<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;


class PeminjamanController extends Controller
{
    public function kembalikan($id)
{
    $peminjaman = Peminjaman::findOrFail($id);

    // Cek apakah statusnya belum dikembalikan
    if ($peminjaman->status !== 'dikembalikan') {
        $peminjaman->status = 'dikembalikan';
        $peminjaman->tanggal_kembali_final = now();

        // Hitung denda jika terlambat
        $tanggalSeharusnyaKembali = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
        $tanggalKembaliSekarang = \Carbon\Carbon::now();

        if ($tanggalKembaliSekarang->greaterThan($tanggalSeharusnyaKembali)) {
            $selisihHari = $tanggalKembaliSekarang->diffInDays($tanggalSeharusnyaKembali);
            $peminjaman->denda = $selisihHari * 500;
        } else {
            $peminjaman->denda = 0;
        }

        $peminjaman->save();

        // Tambahkan stok buku
        $buku = $peminjaman->buku;
        $buku->stok += 1;
        $buku->save();
    }

    return redirect()->route('peminjamans.index')->with('success', 'Buku berhasil dikembalikan, stok ditambahkan, dan denda dihitung.');
}

    

public function data()
{
    $query = Peminjaman::with(['user', 'buku']);

    return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('kode_pinjam', function ($row) {
            return $row->kode_pinjam ?? '-';
        })
        ->addColumn('user', function ($row) {
            return $row->user->name ?? '-';
        })
        ->addColumn('buku', function ($row) {
            return $row->buku->judul ?? '-';
        })
        ->addColumn('status', function ($row) {
            $status = $row->status ?? '-';
            $color = '';
        
            // Ubah 'Menunggu_admin' menjadi 'Menunggu admin'
            if (strtolower($status) === 'menunggu_admin') {
                $status = 'Menunggu';
            }
        
            if (strtolower($status) == 'dikembalikan') {
                $color = 'bg-green-200 text-green-800';
            } elseif (strtolower($status) == 'dipinjam') {
                $color = 'bg-red-200 text-red-800';
            } elseif (strtolower($status) == 'menunggu') {
                $color = 'bg-yellow-200 text-yellow-800';
            } else {
                $color = 'bg-gray-200 text-gray-800';
            }
        
            return '<span class="px-3 py-1 rounded text-sm font-semibold ' . $color . '">' . ucfirst($status) . '</span>';
        })
        
        ->addColumn('tanggal_kembali_final', function ($row) {
            return $row->tanggal_kembali_final
                ? \Carbon\Carbon::parse($row->tanggal_kembali_final)->translatedFormat('d F Y')
                : '-';
        })
        ->addColumn('denda', function ($row) {
            return $row->denda ?? '-';
        })
        ->addColumn('aksi', function ($row) {
            $action = '<div class="flex flex-col md:flex-row gap-2">';

            if ($row->status === 'menunggu_admin') {
                $action .= '
                    <a href="' . route('peminjamans.kembalikan', $row->id) . '" 
                       onclick="return confirm(\'Yakin ingin mengembalikan buku ini?\')"
                       class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm transition text-center">
                       🔄 Kembalikan
                    </a>';
            }

            $action .= '
                <a href="' . route('peminjamans.edit', $row->id) . '" 
                   class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500 text-sm transition text-center">
                   ✏️ Edit
                </a>
                <form action="' . route('peminjamans.destroy', $row->id) . '" method="POST" 
                      onsubmit="return confirm(\'Yakin ingin menghapus?\')" class="inline">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" 
                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm transition w-full text-center">
                        🗑️ Hapus
                    </button>
                </form>
            </div>';

            return $action;
        })
        ->editColumn('tanggal_pinjam', function ($row) {
            return \Carbon\Carbon::parse($row->tanggal_pinjam)->translatedFormat('d F Y');
        })
        ->editColumn('tanggal_kembali', function ($row) {
            return \Carbon\Carbon::parse($row->tanggal_kembali)->translatedFormat('d F Y');
        })
        ->rawColumns(['aksi', 'status'])
        ->filter(function ($query) {
            if (request()->has('search') && $search = request('search')['value']) {
                $query->where('kode_pinjam', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })->orWhereHas('buku', function ($q) use ($search) {
                        $q->where('judul', 'like', "%{$search}%");
                    });
            }

            if (request()->has('status') && in_array(request('status'), ['dipinjam', 'dikembalikan','menunggu_admin'])) {
                $query->where('status', request('status'));
            }
        })
        ->make(true);
}



    

    public function index()
    {
        $peminjamans = Peminjaman::with(['user', 'buku'])->latest()->get();
        return view('admin.peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $users = User::all();
        $bukus = Buku::where('stok', '>', 0)->get();

        return view('admin.peminjaman.create', compact('users', 'bukus'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
        ]);
    
        $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
        $tanggalKembali = $request->tanggal_kembali
            ? Carbon::parse($request->tanggal_kembali)
            : $tanggalPinjam->copy()->addDays(10);
    
        $buku = Buku::find($request->buku_id);
        if ($buku->stok <= 0) {
            return redirect()->route('peminjamans.create')->with('error', 'Stok buku tidak mencukupi.');
        }
    
        // Dapatkan bulan dan tahun
        $bulan = $tanggalPinjam->format('m');
        $tahun = $tanggalPinjam->format('Y');
    
        // Cari kode terakhir di bulan dan tahun yang sama
        $lastKode = Peminjaman::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->value('kode_pinjam');
    
        if ($lastKode) {
            // Ambil angka depan dari format XXXX/MM/YYYY
            $lastNumber = (int) explode('/', $lastKode)[0];
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
    
        // Format kode
        $kodePinjam = str_pad($nextNumber, 4, '0', STR_PAD_LEFT) . '/' . $bulan . '/' . $tahun;
    
        // Simpan
        Peminjaman::create([
            'user_id' => $request->user_id,
            'buku_id' => $request->buku_id,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'kode_pinjam' => $kodePinjam,
            'status' => 'dipinjam',
            'denda' => 0,
        ]);
    
        $buku->stok -= 1;
        $buku->save();
    
        return redirect()->route('peminjamans.index')->with('success', 'Peminjaman berhasil ditambahkan.');
    }
    

    

    public function show(Peminjaman $peminjaman)
    {
        return view('admin.peminjamans.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $users = User::all();
        $books = Buku::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'books'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'buku_id' => 'required|exists:bukus,id',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
    ]);

    $peminjaman->user_id = $request->user_id;
    $peminjaman->buku_id = $request->buku_id;
    $peminjaman->tanggal_pinjam = $request->tanggal_pinjam;
    $peminjaman->tanggal_kembali = $request->tanggal_kembali;

    // Hitung ulang denda jika tanggal kembali diatur ulang dan status belum dikembalikan
    if ($request->tanggal_kembali && $peminjaman->status !== 'dikembalikan') {
        $tanggalKembali = Carbon::parse($request->tanggal_kembali);
        $hariIni = Carbon::now();

        if ($hariIni->gt($tanggalKembali)) {
            $terlambat = $hariIni->diffInDays($tanggalKembali);
            $peminjaman->denda = $terlambat * 500;
        } else {
            $peminjaman->denda = 0;
        }
    }

    $peminjaman->save();

    return redirect()->route('peminjamans.index')->with('success', 'Data peminjaman berhasil diperbarui.');
}

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();

        return redirect()->route('peminjamans.index')
            ->with('success', 'Data peminjaman berhasil dihapus.');
    }
}

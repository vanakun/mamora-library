<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Kategori;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BukuImport;

use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Validators\ValidationException;

class BukuController extends Controller
{
    // Tampilkan daftar semua buku
    public function index()
    {
        $bukus = Buku::all();
        return view('admin.buku.index', compact('bukus'));
    }
  
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Buku::with('kategori')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                // Kolom kategori
                ->addColumn('kategori', function ($row) {
                    return $row->kategori ? $row->kategori->nama : '-';
                })

                // Kolom gambar
                ->addColumn('gambar', function ($row) {
                    if ($row->gambar) {
                        $url = asset('storage/gambar_buku/' . $row->gambar);
                        return '<img src="' . $url . '" class="w-16 h-20 object-cover rounded shadow">';
                    }
                    return '<span class="text-gray-400 italic">Tidak ada</span>';
                })

                // Kolom aksi
                ->addColumn('action', function ($row) {
                    $editUrl = route('bukus.edit', $row->id);
                    $deleteUrl = route('bukus.destroy', $row->id);

                    return '
                        <div class="flex gap-2 justify-center">
                            <a href="' . $editUrl . '" 
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold rounded shadow transition duration-300">
                                ✏️ Edit
                            </a>

                            <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Yakin ingin menghapus?\');">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded shadow transition duration-300">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    ';
                })

                // Raw HTML
                ->rawColumns(['gambar', 'action'])

                ->make(true);
        }
    }

    // Tampilkan form tambah buku
    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.buku.create', compact('kategoris'));
    }

    // Simpan buku baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'stok' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);
    
        // Handle upload gambar jika ada
        $namaFileGambar = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaFileGambar = time() . '_' . $gambar->getClientOriginalName();
            $gambar->storeAs('public/gambar_buku', $namaFileGambar);
        }
    
        // Simpan data buku ke database
        Buku::create([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'tahun_terbit' => $request->tahun_terbit,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
            'gambar' => $namaFileGambar,
        ]);
    
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }
    


    // Tampilkan form edit
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();

        return view('admin.buku.edit', compact('buku', 'kategoris'));
    }

    // Update buku
    public function update(Request $request, Buku $buku)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'penulis' => 'required|string|max:255',
        'tahun_terbit' => 'required|numeric',
        'stok' => 'required|numeric',
        'kategori_id' => 'required|exists:kategoris,id', // Validasi kategori yang dipilih
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
    ]);

    // Jika ada gambar baru, upload dan simpan pathnya
    $namaFileGambar = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaFileGambar = time() . '_' . $gambar->getClientOriginalName();
            $gambar->storeAs('public/gambar_buku', $namaFileGambar);
        }

    // Update data buku
    $buku->update([
        'judul' => $request->judul,
        'penulis' => $request->penulis,
        'tahun_terbit' => $request->tahun_terbit,
        'stok' => $request->stok,
        'kategori_id' => $request->kategori_id,
        'gambar' => $namaFileGambar,
    ]);

    return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui.');
}


    // Hapus buku
    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    public function import(Request $request)
    {
    $request->validate([
        'file' => 'required|mimes:xls,xlsx'
    ]);

    Excel::import(new BukuImport, $request->file('file'));

    return redirect()->back()->with('success', 'Data berhasil diimpor.');

    }

}

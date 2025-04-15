<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        return view('admin.kategori.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori::latest()->get();
    
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="flex justify-center gap-2">
                            <button onclick="editKategori('.$row->id.')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">Edit</button>
                            <button onclick="deleteKategori('.$row->id.')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                            <button onclick="showDetail('.$row->id.')" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">Detail</button>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        abort(403); // kalau bukan AJAX
    }
    
    public function showDetail($id)
    {
        $kategori = Kategori::findOrFail($id);
        return response()->json($kategori);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori = Kategori::create([
            'nama' => $request->nama,
        ]);

        return response()->json(['message' => 'Kategori berhasil ditambah']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('kategoris.index')->with('successupdate', 'kategori berhasil diupdate!');
        
    }

    public function show($id)
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan.'], 404);
        }

        return response()->json($kategori);
    }

    public function edit($id)
{
    $kategori = Kategori::findOrFail($id);
    return view('admin.kategori.edit', compact('kategori'));
}


    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        
        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
    
}

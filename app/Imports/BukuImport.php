<?php
namespace App\Imports;

use App\Models\Buku;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;

class BukuImport implements ToModel
{
    public function model(array $row)
    {
        // Cari kategori berdasarkan nama
        $kategori = Kategori::where('nama', $row[5])->first();

        // Jika tidak ditemukan, bisa dilewati atau dibuat default
        if (!$kategori) {
            return null; // skip baris ini
        }

        return new Buku([
            'judul'         => $row[0],
            'gambar'        => $row[1],
            'penulis'       => $row[2],
            'tahun_terbit'  => $row[3],
            'stok'          => $row[4],
            'kategori_id'   => $kategori->id,
        ]);
    }
}

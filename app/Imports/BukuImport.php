<?php
namespace App\Imports;

use App\Models\Buku;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;

class BukuImport implements ToModel
{
    public $emptyRowCount = 0; // <--- Penting! Tambahkan properti ini

    public function model(array $row)
    {
        // Jika baris kosong
        if (empty(array_filter($row))) {
            $this->emptyRowCount++;
            return null;
        }

        $kategori = Kategori::where('nama', $row[5])->first();

        if (!$kategori) {
            return null;
        }

        return new Buku([
            'judul'         => $row[0],
            'gambar'        => $row[1],
            'penulis'       => $row[2],
            'tahun_terbit'  => $row[3],
            'stok'          => $row[4],
            'kategori_id'   => $kategori->id,
            'status'          => $row[6],
        ]);
    }
}

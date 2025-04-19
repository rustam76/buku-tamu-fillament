<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\KategoriTamu;
use App\Models\Tamu;

class FormBukuTamuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurusans = Jurusan::all();
        $kategoriTamus = KategoriTamu::all();
        return view('welcome', compact('kategoriTamus', 'jurusans'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => ['required', 'regex:/^(\+?[0-9]{1,4})?(\(?\d{1,3}\)?[\s\-]?)?[\d\s\-]{7,10}$/'],
            'address' => 'required',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kategori_tamu_id' => 'required',
            'kategori_tamu_lainnya' => 'nullable|required_if:kategori_tamu_id,Lainnya',
            'jurusan_id' => 'required',
            'jurusan_lainnya' => 'nullable|required_if:jurusan_id,Lainnya',
            'description' => 'required',
        ]);
        if ($validatedData) {
            try {
                // Validate the incoming request



                // Retrieve related models for kategori_tamu and jurusan
                $kategoriTamu = KategoriTamu::find($validatedData['kategori_tamu_id']);
                if ($kategoriTamu && strtolower($kategoriTamu->name) !== 'lainnya') {
                    $validatedData['kategori_tamu_lainnya'] = null; // Reset if not "Lainnya"
                }

                $jurusan = Jurusan::find($validatedData['jurusan_id']);
                if ($jurusan && strtolower($jurusan->name) !== 'lainnya') {
                    $validatedData['jurusan_lainnya'] = null; // Reset if not "Lainnya"
                }

                // Assuming you are saving the data in a model like 'Guest'
                $guest = new Tamu();
                $guest->name = $validatedData['name'];
                $guest->email = $validatedData['email'];
                $guest->phone = $validatedData['phone'];
                $guest->address = $validatedData['address'];
                $guest->kategori_tamu_id = $validatedData['kategori_tamu_id'];
                $guest->jenis_kelamin = $validatedData['jenis_kelamin'];
                $guest->kategori_tamu_lainnya = $validatedData['kategori_tamu_lainnya'];
                $guest->jurusan_id = $validatedData['jurusan_id'];
                $guest->jurusan_lainnya = $validatedData['jurusan_lainnya'];
                $guest->description = $validatedData['description'];

                $guest->save();

                // Flash success message
                // return redirect()->route('form-buku-tamu.index')->with('success', 'Pesan Berhasil Dikirim');
                toast('Berhasil Daftar Buku Tamu', 'success');
                return back()->with('success', 'Pesan Berhasil Dikirim');
            } catch (\Throwable $th) {
                // Flash error message
                // dd($th->getMessage());
                // toast($th->getMessage(), 'error');
                return back()->with('error', $th->getMessage());
            }
        }
    }
}

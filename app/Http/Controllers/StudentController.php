<?php
// LANGKAH 1: Debug di StudentController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Activity;
use App\Models\DataJurusan;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{

    public function testJurusan()
    {
        $jurusans = DataJurusan::all();
        return response()->json($jurusans);
    }
    public function create()
    {
        try {
            $jurusans = DataJurusan::all();

            // DEBUG: Pastikan data ada sebelum dikirim ke view

            // dd($jurusans); // Uncomment ini untuk debugging

            return view('index', compact('jurusans'));
        } catch (\Exception $e) {
            $jurusans = collect();
            return view('index', compact('jurusans'))->with('error', 'Gagal memuat data jurusan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Ambil data jurusan lagi untuk ditampilkan jika ada error validasi
        $jurusans = DataJurusan::all();

        $request->validate([
            'name' => 'required',
            'NIM' => 'required|unique:students,NIM',
            'email' => 'required|email|unique:students,email',
            'tanggal_lahir' => 'required|date',
            'jurusan_id' => 'required|exists:data_jurusans,id',
            'telepon' => 'required',
            'kesukaan' => 'required',
            'alamat' => 'required',
            'alamat_link' => 'nullable',
        ]);

        try {
            // Simpan data mahasiswa
            $student = Student::create([
                'id_user' => Auth::id() ?? 1,
                'name' => $request->name,
                'email' => $request->email,
                'NIM' => $request->NIM,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jurusan_id' => $request->jurusan_id,
                'telepon' => $request->telepon,
                'kesukaan' => $request->kesukaan,
                'alamat' => $request->alamat,
                'alamat_link' => $request->alamat_link, // Link Google Maps
            ]);

            // Catat aktivitas
            Activity::create([
                'id_user' => $student->id_user,
                'activity' => 'User sudah membuat data mahasiswa',
            ]);

            return redirect()->route('students.create')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()
                ->route('students.create')
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])
                ->with('jurusans', $jurusans); // Kirim data jurusan kembali
        }
    }
}

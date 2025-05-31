<?php

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
            return view('index', compact('jurusans'));
        } catch (\Exception $e) {
            $jurusans = collect();
            return view('index', compact('jurusans'))->with('error', 'Gagal memuat data jurusan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // DEBUG: Uncomment untuk melihat data yang diterima
        // dd($request->all());

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
            'alamat_link' => 'nullable|url',
        ]);

        try {
            // Cek apakah nama sudah ada
            $existingStudent = Student::where('name', $request->name)->first();
            if ($existingStudent) {
                return redirect()->route('profile.show', ['name' => $request->name])
                    ->with('error', 'Data mahasiswa dengan nama ini sudah ada.');
            }

            // Simpan data mahasiswa
            $student = Student::create([
                'id_user' => 1, // Default user ID atau bisa dihapus jika tidak diperlukan
                'name' => $request->name,
                'email' => $request->email,
                'NIM' => $request->NIM,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jurusan_id' => $request->jurusan_id,
                'telepon' => $request->telepon,
                'kesukaan' => $request->kesukaan,
                'alamat' => $request->alamat,
                'alamat_link' => $request->alamat_link,
            ]);

            // DEBUG: Uncomment untuk melihat data yang tersimpan
            // dd($student->toArray());

            // Catat aktivitas
            Activity::create([
                'id_user' => $student->id_user,
                'activity' => 'User sudah membuat data mahasiswa dengan nama: ' . $student->name,
            ]);

            return redirect()->route('profile.show', ['name' => $student->name])
                ->with('success', 'Data mahasiswa berhasil disimpan!');
        } catch (\Exception $e) {
            // DEBUG: Uncomment untuk melihat error detail
            // dd($e->getMessage(), $e->getFile(), $e->getLine());

            return redirect()
                ->route('students.create')
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()])
                ->with('jurusans', $jurusans);
        }
    }

    // Method untuk menampilkan profile user berdasarkan nama
    public function showProfile($name = null)
    {
        try {
            // Jika nama tidak diberikan dalam URL, ambil dari request atau session
            if (!$name) {
                $name = request('name') ?? session('student_name');
            }

            if (!$name) {
                return redirect()->route('students.create')
                    ->with('error', 'Nama mahasiswa tidak ditemukan. Silakan isi data terlebih dahulu.');
            }

            // DEBUG: Uncomment untuk debugging
            // dd($name);

            // Ambil data mahasiswa berdasarkan nama
            $student = Student::with('jurusan')
                ->where('name', $name)
                ->first();

            // DEBUG: Uncomment untuk melihat data student
            // dd($student);

            // Jika tidak ada data mahasiswa dengan nama tersebut
            if (!$student) {
                return redirect()->route('students.create')
                    ->with('error', 'Data mahasiswa dengan nama "' . $name . '" tidak ditemukan. Silakan isi data terlebih dahulu.');
            }

            // Simpan nama di session untuk akses selanjutnya
            session(['student_name' => $name]);

            return view('profile_user', compact('student'));
        } catch (\Exception $e) {
            // DEBUG: Uncomment untuk melihat error
            // dd($e->getMessage(), $e->getFile(), $e->getLine());

            return redirect()->route('students.create')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk edit profile berdasarkan nama
    public function editProfile($name = null)
    {
        try {
            // Jika nama tidak diberikan dalam URL, ambil dari request atau session
            if (!$name) {
                $name = request('name') ?? session('student_name');
            }

            if (!$name) {
                return redirect()->route('students.create')
                    ->with('error', 'Nama mahasiswa tidak ditemukan.');
            }

            $student = Student::with('jurusan')
                ->where('name', $name)
                ->first();

            if (!$student) {
                return redirect()->route('students.create')
                    ->with('error', 'Data mahasiswa dengan nama "' . $name . '" tidak ditemukan.');
            }

            $jurusans = DataJurusan::all();

            return view('edit_profile', compact('student', 'jurusans'));
        } catch (\Exception $e) {
            return redirect()->route('profile.show', ['name' => $name])
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // method untuk logout
    public function logout()
    {
        Auth::logout();

        // Hapus semua session
        session()->flush();

        // Atau hapus session tertentu saja
        // session()->forget('student_name');

        // Regenerate session ID untuk keamanan
        session()->regenerate();
        return redirect()->route('/')->with('success', 'Anda telah berhasil logout.');
    }

    // Method untuk update profile berdasarkan nama
    public function updateProfile(Request $request)
    {
        $studentName = $request->input('original_name') ?? session('student_name');

        if (!$studentName) {
            return redirect()->route('students.create')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $student = Student::where('name', $studentName)->first();

        if (!$student) {
            return redirect()->route('students.create')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required',
            'NIM' => 'required|unique:students,NIM,' . $student->id,
            'email' => 'required|email|unique:students,email,' . $student->id,
            'tanggal_lahir' => 'required|date',
            'jurusan_id' => 'required|exists:data_jurusans,id',
            'telepon' => 'required',
            'kesukaan' => 'required',
            'alamat' => 'required',
            'alamat_link' => 'nullable|url',
        ]);

        try {
            // Update data mahasiswa
            $student->update([
                'name' => $request->name,
                'email' => $request->email,
                'NIM' => $request->NIM,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jurusan_id' => $request->jurusan_id,
                'telepon' => $request->telepon,
                'kesukaan' => $request->kesukaan,
                'alamat' => $request->alamat,
                'alamat_link' => $request->alamat_link,
            ]);

            // Update session jika nama berubah
            session(['student_name' => $request->name]);

            // Catat aktivitas
            Activity::create([
                'id_user' => $student->id_user,
                'activity' => 'User telah mengupdate data mahasiswa: ' . $request->name,
            ]);

            return redirect()->route('profile.show', ['name' => $request->name])
                ->with('success', 'Data mahasiswa berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()
                ->route('profile.edit', ['name' => $studentName])
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
    }
}

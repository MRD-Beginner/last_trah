<?php

namespace App\Http\Controllers;

use App\Models\Anggota_Keluarga;
use App\Models\Partner;
use Illuminate\Http\Request;

class SilsilahController extends Controller
{
    public function index() {
        
    }
    public function create_anggota_keluarga(Request $request) 
    {
        $validated = $request->validate([
            'nama_anggota_keluarga' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'status_kehidupan' => 'required|in:Hidup,Wafat',
            'tanggal_kematian' => 'nullable|date|required_if:status_kehidupan,Wafat',
            'alamat' => 'required|string|max:255',
            'urutan' => 'required|string',
            'tree_id' => 'required|string',
            'keluarga_image_link' => 'nullable|string',
            'parent1_id' => 'nullable|exists:anggota_keluarga,id',
            'parent2_id' => 'nullable',
        ]);

        $anggota_keluarga = Anggota_Keluarga::create([
            'nama' => $validated['nama_anggota_keluarga'], // Changed from 'name' to 'nama' to match migration
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'status_kehidupan' => $validated['status_kehidupan'],
            'tanggal_kematian' => $validated['tanggal_kematian'],
            'alamat' => $validated['alamat'],
            'photo' => $validated['keluarga_image_link'], 
            'urutan' => $validated['urutan'], // Added missing required field
            'tree_id' => $validated['tree_id'], // Added missing required field (adjust as needed)
            'parent_id' => $validated['parent1_id'] ?? null,
            'parent_partner_id' => $validated['parent2_id'] ?? null,
        ]);

        $anggota_keluarga -> save();

        return redirect()->back()
            ->with('success', 'Anggota keluarga berhasil ditambahkan'); // Changed message to be more specific
    }
        
    public function update_anggota_keluarga(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_anggota_keluarga_edit' => 'required|string|max:255',
            'jenis_kelamin_edit' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir_edit' => 'nullable|date',
            'status_kehidupan_edit' => 'required',
            'tanggal_kematian_edit' => 'nullable|date', // Perbaikan disini
            'alamat_edit' => 'required|string|max:255',
            'urutan_edit' => 'required|string',
            'tree_id' => 'required|exists:trah,id',
            'keluarga_image_edit' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Perbaikan disini
            'parent_id_edit' => 'nullable|exists:anggota_keluarga,id',
            'parent2_id_edit' => 'nullable'
        ]);

        $anggota = Anggota_Keluarga::findOrFail($id);

        // Handle file upload - perbaikan nama field
        if ($request->hasFile('keluarga_image_edit')) { // Sesuaikan dengan nama di form
            $photoPath = $request->file('keluarga_image_edit')->store('anggota_keluarga', 'public');
            $anggota->photo = $photoPath;
        }

        // Update anggota keluarga
        $anggota->update([
            'nama' => $validated['nama_anggota_keluarga_edit'],
            'jenis_kelamin' => $validated['jenis_kelamin_edit'],
            'tanggal_lahir' => $validated['tanggal_lahir_edit'],
            'status_kehidupan' => $validated['status_kehidupan_edit'],
            'tanggal_kematian' => $validated['tanggal_kematian_edit'],
            'alamat' => $validated['alamat_edit'],
            'urutan' => $validated['urutan_edit'],
            'tree_id' => $validated['tree_id'],
            'parent_id' => $validated['parent_id_edit'] ?? null,
            'parent_partner_id' => $validated['parent2_id_edit'] ?? null,
        ]);

        return redirect()->back()
            ->with('success', 'Data anggota keluarga berhasil diperbarui');
    }
    public function delete_anggota_keluarga($id) {
        try {
            $anggota = Anggota_Keluarga::findOrFail($id);
            $anggota->delete();

            return redirect()->back()
            ->with('success', 'Data anggota keluarga berhasil diperbarui');
                
        } catch (\Exception $e) {
            return redirect()->route('trah.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
    public function create_pasangan_anggota_keluarga(Request $request) {
        $validated = $request->validate([
            'nama_pasangan_anggota_keluarga' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'nullable|date',
            'status_kehidupan' => 'required|in:Hidup,Wafat',
            'tanggal_kematian' => 'nullable|date|required_if:status_kehidupan,Wafat',
            'urutan' => 'required|string',
            'keluarga_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'partner_id' => 'nullable|exists:anggota_keluarga,id'
        ]);

        $photoPath = null;
        if ($request->hasFile('partner_image')) {
            $photoPath = $request->file('partner_image')->store('partner', 'public');
        }
        

        // Create new anggota keluarga
        $pasangan = Partner::create([
            'nama' => $validated['nama_pasangan_anggota_keluarga'], // Changed from 'name' to 'nama' to match migration
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'status_kehidupan' => $validated['status_kehidupan'],
            'tanggal_kematian' => $validated['tanggal_kematian'],
            'photo' => $photoPath, 
            'urutan_anak' => $validated['urutan'], // Added missing required field
            'anggota_keluarga_id' => $validated['partner_id'] ?? null
        ]);

        $pasangan -> save();

        return redirect()->back()
            ->with('success', 'Anggota keluarga berhasil ditambahkan');
    }
    public function edit_pasangan_anggota_keluarga() {
        
    }
    public function update_pasangan_anggota_keluarga(Request $request, $id) {

    $partner = Partner::findOrFail($id);

        $validated = $request->validate([
        'tree_id' => 'required|exists:trah,id',
        'nama_pasangan_edit' => 'required|string|max:255',
        'jenis_kelamin_edit' => 'required|in:Laki-laki,Perempuan',
        'tanggal_lahir_edit' => 'nullable|date',
        'partner_id_edit' => 'required',
        'urutan_edit' => 'required|integer|min:1|max:14',
        'status_kehidupan_edit' => 'required|in:Hidup,Wafat',
        'tanggal_kematian_edit' => 'nullable|date|required_if:status_kehidupan_edit,Wafat',
        // 'alamat_edit' => 'required|string',
        'foto_pasangan_edit' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);


    // Handle file upload
    if ($request->hasFile('foto_pasangan_edit')) {
        // Delete old photo if exists
        if ($partner->photo) {
            Storage::disk('public')->delete($partner->photo);
        }
        
        $path = $request->file('foto_pasangan_edit')->store('partner_images', 'public');
        $validated['photo'] = $path;
    }

    // Update partner data
    $partner->update([
        'anggota_keluarga_id' => $validated['partner_id_edit'],
        'nama' => $validated['nama_pasangan_edit'],
        'jenis_kelamin' => $validated['jenis_kelamin_edit'],
        'tanggal_lahir' => $validated['tanggal_lahir_edit'],
        'urutan' => $validated['urutan_edit'],
        'status_kehidupan' => $validated['status_kehidupan_edit'],
        'tanggal_kematian' => $validated['status_kehidupan_edit'] == 'Wafat' ? $validated['tanggal_kematian_edit'] : null,
        // 'alamat' => $validated['alamat_edit'],
        'photo' => $validated['photo'] ?? $partner->photo
    ]);

    return redirect()->back()->with('success', 'Data pasangan berhasil diperbarui');
    }
    public function delete_pasangan_anggota_keluarga($id) {
        $partner = Partner::findOrFail($id);

        $partner->delete();

        return redirect()->back()->with('success', 'Pasangan berhasil dihapus');
    }
}

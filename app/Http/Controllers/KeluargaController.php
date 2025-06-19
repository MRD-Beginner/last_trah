<?php

namespace App\Http\Controllers;

use App\Models\Anggota_Keluarga;
use App\Models\Partner;
use App\Models\trah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KeluargaController extends Controller
{
    public function hubungan($id, Request $request)
    {
        $trah = Trah::with(['anggotaKeluarga' => function ($query) {
            $query->orderBy('urutan');
        }])->findOrFail($id);

        $tree_id = $id;
        $anggota_keluarga = $trah->anggotaKeluarga;

        $person1 = null;
        $person2 = null;
        $relationshipDetails = null;
        $relationshipDetailsReversed = null;
        $path = null;
        $pathRev = null;

        if ($request->has('compare') && $request->filled(['name1', 'name2'])) {
            $person1 = Anggota_Keluarga::where('nama', $request->name1)
                ->where('tree_id', $tree_id)
                ->first();
            $person2 = Anggota_Keluarga::where('nama', $request->name2)
                ->where('tree_id', $tree_id)
                ->first();

            if ($person1 && $person2) {
                $logicController = new \App\Http\Controllers\LogicController;

                // Arah Person1 -> Person2 BFS
                $path = $logicController->bfs($person1, $person2->id);
                $relationshipDetails = $path
                    ? $logicController->relationshipPath($path, $person1->nama, $person2->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];

                // Arah Person2 -> Person1 (dibalik)
                $pathRev = $logicController->bfs($person2, $person1->id);
                $relationshipDetailsReversed = $pathRev
                    ? $logicController->relationshipPath($pathRev, $person2->nama, $person1->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];
            }
        }

        return view('detail.hubungan', [
            'trah' => $trah,
            'anggota_keluarga' => $anggota_keluarga,
            'tree_id' => $tree_id,
            'person1' => $person1,
            'person2' => $person2,
            'relationshipDetails' => $relationshipDetails,
            'relationshipDetailsReversed' => $relationshipDetailsReversed,
            'path' => $path,
            'pathRev' => $pathRev,
            'name1' => $request->name1,
            'name2' => $request->name2
        ]);
    }

    public function verifyPassword(Request $request, $id)
    {
        $trah = Trah::findOrFail($id);

        // Verifikasi password (gunakan Hash::check jika password di-hash)
        if ($request->password === $trah->password) {
            // Simpan di session bahwa user sudah terautentikasi
            session(['trah_authenticated_' . $id => true]);

            return redirect()->route('keluarga.detail.private', $id);
        }

        return back()->with('error', 'Password salah');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner' => 'required|string|max:255',
            'password' => 'nullable|string|min:6'
        ]);

        $visibility = empty($validated['password']);

        $family = trah::create([
            'trah_name' => $validated['family_name'],
            'description' => $validated['description'] ?? null,
            'created_by' => $validated['owner'],
            'password' => $validated['password'] ?? null,
            'visibility' => $visibility ? 'public' : 'private',
        ]);

        $family->save();

        return redirect()->route('admin.keluarga')
            ->with('success', 'Keluarga berhasil dibuat');
    }

    public function update(Request $request, $id)
    {
        $family = Trah::findOrFail($id);

        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'password' => 'nullable|string|min:6'
        ]);

        $visibility = empty($validated['password']) ? 'public' : 'private';

        $updateData = [
            'trah_name' => $validated['family_name'],
            'description' => $validated['description'] ?? null,
            'visibility' => $visibility
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        } else {
            unset($updateData['password']);
        }

        $family->update($updateData);

        return redirect()->route('admin.keluarga')
            ->with('success', 'Data keluarga berhasil diperbarui');
    }

    public function delete($id)
    {
        try {
            $trah = Trah::findOrFail($id);
            $trah->delete();

            return redirect()->route('admin.keluarga')
                ->with('success', 'Data trah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('trah.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function detail(Request $request, $id)
    {
        $trah = Trah::with('anggotaKeluarga')->findOrFail($id);
        $anggota_keluarga = $trah->anggotaKeluarga;
        $trah_id = $id;
        $rootMembers = $anggota_keluarga->whereNull('parent_id');

        // Panggil LogicController
        $logic = new \App\Http\Controllers\LogicController();
        $comparison = $logic->compare($request, $id);

        return view('detail.public_detail', [
            'trah_id' => $trah_id,
            'rootMembers' => $rootMembers,
            'trah' => $trah,
            'anggota_keluarga' => $anggota_keluarga,
            ...$comparison // Spread operator untuk unpack array
        ]);
    }

    public function showPublic($id)
    {
        $trah = Trah::with(['anggotaKeluarga' => function ($query) {
            $query->orderBy('urutan');
        }])->findOrFail($id);

        $anggota_keluarga = $trah->anggotaKeluarga;
        $pasangan_keluarga = Partner::whereIn('anggota_keluarga_id', $anggota_keluarga->pluck('id'))
            ->orderBy('nama')
            ->get();

        // Root member (anggota tanpa parent_id) dari trah ini saja
        $rootMember = $anggota_keluarga->whereNull('parent_id');
        $rootPartner = $pasangan_keluarga;

        return view('detail.detaill', [
            'trah' => $trah,
            'allTrah' => Trah::all(),
            'anggota_keluarga' => $anggota_keluarga,
            'pasangan_keluarga' => $pasangan_keluarga,
            'rootMember' => $rootMember,
            'rootPartner' => $rootPartner,
            // 'slug' => Str::slug($trah->trah_name)
        ]);
    }

    public function showPublicComparison(Request $request, $id)
    {
        $baseData = $this->showPublic($id)->getData();

        if ($request->has('compare') && $request->filled(['name1', 'name2'])) {
            $person1 = Anggota_Keluarga::where('nama', $request->name1)
                ->where('tree_id', $id)
                ->first();
            $person2 = Anggota_Keluarga::where('nama', $request->name2)
                ->where('tree_id', $id)
                ->first();

            if ($person1 && $person2) {
                $logicController = new \App\Http\Controllers\LogicController;

                // Arah Person1 -> Person2 BFS
                $path1 = $logicController->bfs($person1, $person2->id);
                $baseData['relationshipDetails'] = $path1
                    ? $logicController->relationshipPath($path1, $person1->nama, $person2->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];

                // Arah Person2 -> Person1 (dibalik)
                $path2 = $logicController->bfs($person2, $person1->id);
                $baseData['relationshipDetailsReversed'] = $path2
                    ? $logicController->relationshipPath($path2, $person2->nama, $person1->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];
            }
        }

        return view('detail.detaill', (array) $baseData);
    }

    public function detail_public(Request $request, $id)
    {
        $trah = Trah::with(['anggotaKeluarga' => function ($query) {
            $query->orderBy('urutan');
        }])->findOrFail($id);
        $tree_id = $id;
        $anggota_keluarga = $trah->anggotaKeluarga;
        $pasangan_keluarga = Partner::whereIn('anggota_keluarga_id', $anggota_keluarga->pluck('id'))
            ->orderBy('nama')
            ->get();
        // Root member (anggota tanpa parent_id) dari trah ini saja
        $rootMember = $anggota_keluarga->whereNull('parent_id');
        $rootPartner = $pasangan_keluarga;

        $person1 = null;
        $person2 = null;
        $relationshipDetails = null;
        $relationshipDetailsReversed = null;

        if ($request->has('compare') && $request->filled(['name1', 'name2'])) {
            $person1 = Anggota_Keluarga::where('nama', $request->name1)->where('tree_id', $tree_id)->first();
            $person2 = Anggota_Keluarga::where('nama', $request->name2)->where('tree_id', $tree_id)->first();

            if ($person1 && $person2) {
                $logicController = new \App\Http\Controllers\LogicController;

                // Arah Person1 -> Person2 BFS
                $path1 = $logicController->bfs($person1, $person2->id);
                $relationshipDetails = $path1
                    ? $logicController->relationshipPath($path1, $person1->nama, $person2->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];

                // Arah Person2 -> Person1 (dibalik)
                $path2 = $logicController->bfs($person2, $person1->id);
                $relationshipDetailsReversed = $path2
                    ? $logicController->relationshipPath($path2, $person2->nama, $person1->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];
            }
        }

        return view('detail.public_detail', [
            'trahs' => $trah,
            'trah' => $trah,
            'anggota_keluarga' => $anggota_keluarga,
            'existingMembers' => $anggota_keluarga,
            'rootMember' => $rootMember,
            'rootPartner' => $rootPartner,
            'pasangan_keluarga' => $pasangan_keluarga,
            'relationshipDetails' => $relationshipDetails,
            'relationshipDetailsReversed' => $relationshipDetailsReversed,
            'tree_id' => $tree_id // Pastikan tree_id dikirim dengan nama key yang benar
        ]);
    }

    public function pohon($id)
    {
        $tree_id = $id;
        $trah = Trah::with(['anggotaKeluarga' => function ($query) {
            $query->orderBy('urutan');
        }])->findOrFail($tree_id);

        $anggota_keluarga = $trah->anggotaKeluarga;
        $pasangan_keluarga = Partner::whereIn('anggota_keluarga_id', $anggota_keluarga->pluck('id'))
            ->orderBy('nama')
            ->get();

        // Anggota tanpa parent (root members)
        $rootMember = $anggota_keluarga->whereNull('parent_id');
        $rootPartner = $pasangan_keluarga;

        return view('detail.pohon', [
            'trah' => $trah,
            'rootMember' => $rootMember,
            'rootPartner' => $rootPartner,
            'tree_id' => $tree_id
        ]);
    }



    public function detail_private($id, Request $request)
    {
        $trah = Trah::with(['anggotaKeluarga' => function ($query) {
            $query->orderBy('urutan');
        }])->findOrFail($id);
        $tree_id = $id;
        $anggota_keluarga = $trah->anggotaKeluarga;
        $pasangan_keluarga = Partner::whereIn('anggota_keluarga_id', $anggota_keluarga->pluck('id'))
            ->orderBy('nama')
            ->get();
        // Root member (anggota tanpa parent_id) dari trah ini saja
        $rootMember = $anggota_keluarga->whereNull('parent_id');
        $rootPartner = $pasangan_keluarga;

        $person1 = null;
        $person2 = null;
        $relationshipDetails = null;
        $relationshipDetailsReversed = null;

        if ($request->has('compare') && $request->filled(['name1', 'name2'])) {
            $person1 = Anggota_Keluarga::where('nama', $request->name1)->where('tree_id', $tree_id)->first();
            $person2 = Anggota_Keluarga::where('nama', $request->name2)->where('tree_id', $tree_id)->first();

            if ($person1 && $person2) {
                $logicController = new \App\Http\Controllers\LogicController;

                // Arah Person1 -> Person2 BFS
                $path1 = $logicController->bfs($person1, $person2->id);
                $relationshipDetails = $path1
                    ? $logicController->relationshipPath($path1, $person1->nama, $person2->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];

                // Arah Person2 -> Person1 (dibalik)
                $path2 = $logicController->bfs($person2, $person1->id);
                $relationshipDetailsReversed = $path2
                    ? $logicController->relationshipPath($path2, $person2->nama, $person1->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];
            }
        }

        return view('detail.private_detail', [
            'trahs' => $trah,
            'trah' => $trah,
            'anggota_keluarga' => $anggota_keluarga,
            'existingMembers' => $anggota_keluarga,
            'rootMember' => $rootMember,
            'rootPartner' => $rootPartner,
            'pasangan_keluarga' => $pasangan_keluarga,
            'relationshipDetails' => $relationshipDetails,
            'relationshipDetailsReversed' => $relationshipDetailsReversed,
            'tree_id' => $tree_id // Pastikan tree_id dikirim dengan nama key yang benar
        ]);
    }

    public function detail_private_user($id, Request $request)
    {
        $trah = Trah::with(['anggotaKeluarga' => function ($query) {
            $query->orderBy('urutan');
        }])->findOrFail($id);

        // Ambil hanya anggota keluarga yang terkait dengan trah ini
        $anggota_keluarga = $trah->anggotaKeluarga;

        // Ambil partner yang terkait dengan anggota keluarga ini
        $partner = Partner::whereIn('anggota_keluarga_id', $anggota_keluarga->pluck('id'))
            ->orderBy('nama')
            ->get();

        // Root member (anggota tanpa parent_id) dari trah ini saja
        $rootMember = $anggota_keluarga->whereNull('parent_id');

        // Root partner (partner tanpa anggota_keluarga_id) - ini mungkin perlu penyesuaian
        $rootPartner = $partner->whereNull('anggota_keluarga_id');

        return view('detail.public_detail', [
            'trahs' => $trah, // Menggunakan nama variabel yang konsisten
            'trah' => $trah, // Duplikat jika diperlukan untuk kompatibilitas
            'anggota_keluarga' => $anggota_keluarga,
            'existingMembers' => $anggota_keluarga, // Sama dengan anggota_keluarga
            'rootMember' => $rootMember,
            'rootPartner' => $rootPartner,
            'partner' => $partner
        ]);
    }

    public function checkPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $trah = Trah::findOrFail($id);

        // Debugging - Hapus setelah testing

        if (Hash::check($request->password, $trah->password)) {
            session(["trah_verified_$id" => true]);
            return redirect()->route('keluarga.detail.private', $id);
        }

        return back()->withErrors(['password' => 'Password salah!']); // Kembali ke modal dengan error
    }

    public function checkPassword2(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        $trah = Trah::findOrFail($id);

        if (Hash::check($request->password, $trah->password)) {
            // Simpan status verifikasi di session
            session(["trah_verified_$id" => true]);
            return redirect()->route('user.keluarga.detail.private', $id);
        }

        return redirect()->route('user.keluarga')
            ->with('error', 'Password salah');
    }

    public function store_user(Request $request)
    {
        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner' => 'required|string|max:255',
            'password' => 'nullable|string|min:6'
        ]);

        $visibility = empty($validated['password']);

        $family = trah::create([
            'trah_name' => $validated['family_name'],    // mengambil dari input form
            'description' => $validated['description'] ?? null, // dari input dengan fallback null
            'created_by' => $validated['owner'],          // dari input form
            'password' => $validated['password'] ?? null,  // dari input dengan fallback null
            'visibility' => $visibility ? 'public' : 'private',                // dari perhitungan sebelumnya
        ]);
        return redirect()->route('user.keluarga')
            ->with('success', 'Keluarga berhasil dibuat');
    }

    public function update_user(Request $request, $id)
    {
        $family = Trah::findOrFail($id);

        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'password' => 'nullable|string|min:6'
        ]);

        $visibility = empty($validated['password']) ? 'public' : 'private';

        $updateData = [
            'trah_name' => $validated['family_name'],
            'description' => $validated['description'] ?? null,
            'visibility' => $visibility
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        } else {
            unset($updateData['password']);
        }

        $family->update($updateData);

        return redirect()->route('user.keluarga')
            ->with('success', 'Data keluarga berhasil diperbarui');
    }

    public function delete_user($id)
    {
        try {
            $trah = Trah::findOrFail($id);
            $trah->delete();

            return redirect()->route('user.keluarga')
                ->with('success', 'Data trah berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('trah.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

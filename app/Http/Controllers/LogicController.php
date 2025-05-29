<?php
namespace App\Http\Controllers;

use App\Models\Anggota_Keluarga;
use App\Models\Trah;
use Illuminate\Http\Request;

class LogicController extends Controller
{
    

    
    public function compare(Request $request, $tree_id)
    {
        $person1 = null;
        $person2 = null;
        $relationshipDetails = null;
        $relationshipDetailsReversed = null;
        $path = null;
        $pathReverse = null;

        if ($request->has('compare') && $request->filled(['name1', 'name2'])) {
            $person1 = Anggota_Keluarga::where('nama', $request->name1)
                      ->where('tree_id', $tree_id)->first();
            $person2 = Anggota_Keluarga::where('nama', $request->name2)
                      ->where('tree_id', $tree_id)->first();

            if ($person1 && $person2) {
                // Person1 -> Person2
                $visited = [];
                $path = [];
                $found = $this->dfs($person1, $person2->id, $visited, $path);
                $relationshipDetails = $found
                    ? $this->relationshipPath($path, $person1->nama, $person2->nama)
                    : 'Tidak ada hubungan yang ditemukan.';

                // Person2 -> Person1 (reversed)
                $visitedRev = [];
                $pathRev = [];
                $foundRev = $this->dfs($person2, $person1->id, $visitedRev, $pathRev);
                $relationshipDetailsReversed = $foundRev
                    ? $this->relationshipPath($pathRev, $person2->nama, $person1->nama)
                    : 'Tidak ada hubungan yang ditemukan.';
            }
        }

        return [
            'person1' => $person1,
            'person2' => $person2,
            'relationshipDetails' => $relationshipDetails,
            'relationshipDetailsReversed' => $relationshipDetailsReversed,
            'path' => $path ?? null,
            'pathReverse' => $pathRev ?? null
        ];
    }


    public function dfs($current, $targetId, &$visited, &$path)
    {
        if (in_array($current->id, $visited)) return false;
    
        $visited[] = $current->id;
        $path[] = $current;
    
        if ($current->id == $targetId) return true;
    
        // Cek ke atas
        if ($current->parent) {
            if ($this->dfs($current->parent, $targetId, $visited, $path)) return true;
        }
    
        // Cek ke bawah
        foreach ($current->children as $child) {
            if ($this->dfs($child, $targetId, $visited, $path)) return true;
        }
    
        // Cek ke samping 
        if ($current->parent) {
            foreach ($current->parent->children as $sibling) {
                if ($sibling->id != $current->id) {
                    if ($this->dfs($sibling, $targetId, $visited, $path)) return true;
                }
            }
        }
    
        array_pop($path);
        return false;
    }


    public function bfs($start, $targetId)
    {
        $queue = [[$start, [$start]]];
        $visited = [$start->id => true];
    
        while (!empty($queue)) {
            [$current, $path] = array_shift($queue);
    
            if ($current->id == $targetId) {
                return $path;
            }
    
            // Tambahkan anak
            foreach ($current->children as $child) {
                if (!isset($visited[$child->id])) {
                    $visited[$child->id] = true;
                    $queue[] = [$child, array_merge($path, [$child])];
                }
            }
    
            // Tambahkan parent
            if ($current->parent && !isset($visited[$current->parent->id])) {
                $visited[$current->parent->id] = true;
                $queue[] = [$current->parent, array_merge($path, [$current->parent])];
            }
    
            // Tambahkan saudara kandung
            if ($current->parent) {
                foreach ($current->parent->children as $sibling) {
                    if ($sibling->id !== $current->id && !isset($visited[$sibling->id])) {
                        $visited[$sibling->id] = true;
                        $queue[] = [$sibling, array_merge($path, [$sibling])];
                    }
                }
            }
        }
    
        return null;
    }
    

    public function getAncestor(Anggota_Keluarga $person, int $levels): ?Anggota_Keluarga
    {
        $node = $person;
        for ($i = 0; $i < $levels; $i++) {
            if (! optional($node->parent)->id) {
                return null;
            }
            $node = $node->parent;
        }
        return $node;
    }

    // HASIL HUBUNGAN
    public function relationshipResult($path) //ngaruh di hasil hubungan
    {
        $depth = $this->calculateActualDepth($path);
        $first = $path[0];
        $last = end($path);
        $gender = $first->jenis_kelamin; // Menggunakan jenis_kelamin dari model
        
        $relations = [
            -1 => ['Laki-Laki' => 'anak laki laki ', 'Perempuan' => 'anak perempuan '],
            1 => ['Laki-Laki' => 'bapak dari', 'Perempuan' => 'ibu dari'],
            2 => ['Laki-Laki' => 'putu lanang (cucu laki-laki) dari', 'Perempuan' => 'putu wedok (cucu perempuan) dari'],
            -2 => ['Laki-Laki' => 'eyang lanang (kakek) ', 'Perempuan' => 'eyang wedok (nenek) '],
            -3 => ['Laki-Laki' => 'mbah buyut lanang ', 'Perempuan' => 'mbah buyut wedok '],
            3 => ['Laki-Laki' => 'cicit/buyut lanang dari',  'Perempuan' => 'cicit/buyut wedok dari'],
            -4 => ['Laki-Laki' => 'mbah canggah lanang ',  'Perempuan' => 'mbah canggah wedok '],
            4 => ['Laki-Laki' => 'canggah lanang dari', 'Perempuan' => 'canggah wedok dari'],
            -5 => ['Laki-Laki' => 'mbah wareg lanang dari', 'Perempuan' => 'mbah wareg wedok dari'],
            5 => ['Laki-Laki' => 'wareg lanang dari', 'Perempuan' => 'wareg wedok dari'],
            -6 => ['Laki-Laki' => 'mbah uthek-uthek lanang dari', 'Perempuan' => 'mbah uthek-uthek wedok dari'],
            6 => ['Laki-Laki' => 'uthek-uthek lanang dari', 'Perempuan' => 'uthek-uthek wedok dari'],
            -7 => ['Laki-Laki' => 'mbah gantung siwur lanang dari', 'Perempuan' => 'mbah gantung siwur wedok dari'],
            7 => ['Laki-Laki' => 'gantung siwur lanang dari', 'Perempuan' => 'gantung siwur wedok dari'],
            -8 => ['Laki-Laki' => 'mbah gropak santhe lanang dari', 'Perempuan' => 'mbah gropak santhe wedok dari'],
            8 => ['Laki-Laki' => 'cicip moning lanang dari', 'Perempuan' => 'cicip moning wedok dari'],
            -9 => ['Laki-Laki' => 'mbah debog bosok lanang dari', 'Perempuan' => 'mbah debog bosok wedok dari'],
            9 => ['Laki-Laki' => 'petarang bobrok lanang dari', 'Perempuan' => 'petarang bobrok wedok dari'],
            -10 => ['Laki-Laki' => 'mbah galih asem lanang dari', 'Perempuan' => 'mbah galih asem wedok dari'],
            10 => ['Laki-Laki' => 'gropak santhe lanang dari', 'Perempuan' => 'gropak santhe wedok dari'],
            'nak-sanak' => [ 'Laki-Laki' => 'sedulur nak-sanak lanang (sepupu) dengan', 'Perempuan' => 'sedulur nak-sanak wedok (sepupu) dengan'],
            'misanan' => ['Laki-Laki' => 'sedulur misanan lanang (sepupu) dengan',  'Perempuan' => 'sedulur misanan wedok (sepupu) dengan'],
            'mindhoan' => ['Laki-Laki' => 'sedulur mindhoan lanang (sepupu) dengan', 'Perempuan' => 'sedulur mindhoan wedok (sepupu) dengan'],
            'old uncle' => ['Laki-Laki' => 'pakde dari',  'Perempuan' => 'bukde dari'],
            'young uncle' => ['Laki-Laki' => 'paklek dari', 'Perempuan' => 'buklek dari'],
            'ponakan prunan' => ['Laki-Laki' => 'ponakan prunan lanang dari', 'Perempuan' => 'ponakan prunan wedok dari'],
            'ponakan' => ['Laki-Laki' => 'ponakan lanang dari','Perempuan' => 'ponakan wedok dari']
        ];


        //LOGIC NYA   
        // 1. Orang tua langsung
        if ($last->parent_id === $first->id) {
            return $relations[1][$gender]. " {$last->nama}"; // Menggunakan nama bukan name
        }

        // 2. Anak langsung
        if ($first->parent_id === $last->id) {
            $urutan = $first->urutan;
            return $relations[-1][$gender] . " ke-{$urutan} {$last->nama}";
        }

        // 3. Saudara kandung
        if ($depth === 0 && $first->parent_id === $last->parent_id) {
            if ($first->urutan < $last->urutan) {
                return " {$first->nama} ". ($first->gender === 'Laki-Laki' ? 'mas dari' : 'mbak dari')." {$last->nama}" ;
            }
            return ($first->gender === 'Laki-Laki' ? 'adik laki-laki dari' : 'adik perempuan dari')." {$last->nama}";
        }

        // 4. Sepupu  (nak-sanak)
        if ($depth === 0 && optional($first->parent)->parent_id
            && optional($last->parent)->parent_id
            && $first->parent->parent_id === $last->parent->parent_id) {
            $grandf = $first->parent->parent; //mencari kakek/nenek
            $grandfgender = $relations[-2][$grandf->gender]; 
            return $relations['nak-sanak'][$gender] . " {$last->nama} dari {$grandfgender}  {$grandf->nama}";
        }
       

        // 5.  (misanan)
        if ($depth === 0 && optional($first->parent->parent)->parent_id
            && optional($last->parent->parent)->parent_id
            && $first->parent->parent->parent_id === $last->parent->parent->parent_id) {
            $buyut = $first->parent->parent->parent;
            $buyutgender = $relations[-3][$buyut->gender];
            return $relations['misanan'][$gender]. " {$last->nama} dari {$buyutgender} {$buyut->nama}";
        }
        // 6. Mindhoan
        if ($depth === 0 && optional($first->parent->parent->parent)->parent_id
            && optional($last->parent->parent->parent)->parent_id
            && $first->parent->parent->parent->parent_id === $last->parent->parent->parent->parent_id) {
            $canggah = $first->parent->parent->parent->parent;
            $canggahgender = $relations[-4][$canggah->gender];
            return $relations['mindhoan'][$gender]. " {$last->nama} dari {$canggahgender} {$canggah->nama}";
        }

        // 7. Pakde/paklek
        if ($depth === -1 && optional($last->parent)->parent_id
            && $first->parent_id === $last->parent->parent_id) {
            $key = $first->urutan < $last->parent->urutan ? 'old uncle' : 'young uncle';
            return $relations[$key][$first->gender]." {$last->nama}";
        }

        // 8. Keponakan 
        if ($depth === 1 && isset($first->parent)
            && $last->parent_id === $first->parent->parent_id) {
            $key = $last->urutan < $first->parent->urutan ? 'ponakan prunan' : 'ponakan';
            return $relations[$key][$gender]." {$last->nama}";
        }

        // 9. Cucu 
        if ($depth === 2 && $last->parent   && $last->parent->parent ) {
            return $relations[2][$gender]. " {$last->nama}";
        }

        // 10. Kakek/Nenek 
        if ($depth === -2 && $last->parent && $last->parent->parent) {
            return $relations[-2][$gender]. " {$last->nama}";
        }

        //  A. Aunt/Uncle once‐removed (dan lebih)
        if ($depth < 0) {
            // removed = 0 → direct uncle, 1 → once-removed, 2 → twice-removed
            $removed     = abs($depth) - 1;
            $pFirst      = $this->getAncestor($first, 1);            // ayah/ibu first
            $commonAnc   = $this->getAncestor($last, $removed + 2);  // ancestor di level yang sama

            if ($pFirst && $commonAnc
                && $pFirst->parent_id === $commonAnc->parent_id
            ) {
                $u1  = $pFirst->urutan;
                $u2  = optional($last->parent)->urutan;
                $key = $u1 < $u2 ? 'old uncle' : 'young uncle';
                return $relations[$key][$first->gender]." {$last->nama}";
            }
        }

        // test commit

        // test cimmit 2

        //  Niece/Nephew once‐removed (dan lebih)
        if ($depth > 0) {
            // removed = 0 → direct niece, 1 → once-removed, 2 → twice-removed
            $removed     = $depth - 1;
            $pLast       = $this->getAncestor($last, 1);              // ayah/ibu last
            $commonAnc   = $this->getAncestor($first, $removed + 2);  // ancestor level yang sama

            if ($pLast && $commonAnc
                && $pLast->parent_id === $commonAnc->parent_id
            ) {
                $u1  = $first->urutan;
                $u2  = $pLast->urutan;
                $key = $u1 < $u2 ? 'ponakan prunan' : 'ponakan';
                return $relations[$key][$first->gender]." {$last->nama}";
            }
        }



        // Default fallback
        return $relations[$depth][$gender] ?? 'saudara jauh';
    }

    // JALUR HUBUNGAN
    public function relationshipPath($path, $firstPersonName, $secondPersonName) 
    {
        $path = array_reverse($path);
        $firstPerson = $path[0]->nama;
        $lastPerson = end($path)->nama;

        if ($firstPerson !== $firstPersonName) {
            $path = array_reverse($path);
            $firstPerson = $firstPersonName;
            $lastPerson = $secondPersonName;
        }

        $relationshipDescription = $this->relationshipResult($path);
        $detailedPath = [];

        for ($i = 0; $i < count($path) - 1; $i++) {
            $current = $path[$i];
            $next = $path[$i + 1];
        
            // 1. cek orang tua anak
            if ($next->parent_id == $current->id) {
                $relation = ($next->gender == 'Laki-Laki') ? "putra (anak laki-laki) " : "putri (anak perempuan) ";
                $detailedPath[] = " {$next->nama} {$relation}ke-{$next->urutan} dari {$current->nama}";
                continue;
            }
            // 2. cek orang tua anak (reverse)
            elseif ($current->parent_id == $next->id) {
                $relation = ($current->gender == 'Laki-Laki') ? "putra (anak laki-laki) " : "putri (anak perempuan) ";
                $detailedPath[] = " {$current->nama} {$relation}ke-{$current->urutan} dari {$next->nama}";
                continue;
            }

            // 3. cek saudara kandung
            if ($current->parent_id === $next->parent_id) {
                if ($current->urutan < $next->urutan) {
                    // current lebih tua → kakak
                    $role = $current->gender === 'Laki-Laki' ? 'mas' : 'mbak';
                } else {
                    // current lebih muda → adik
                    $role = $current->gender === 'Laki-Laki'
                        ? 'adik laki-laki'
                        : 'adik perempuan';
                }
                $detailedPath[] = " {$current->nama} {$role} dari {$next->nama}";
                continue;
            }
         
        }

        return [
            'relation' => "{$firstPerson} {$relationshipDescription} ",
            'detailedPath' => $detailedPath
        ];
    }

    protected function calculateActualDepth($path)
    {
        if (count($path) < 2) return 0;

        $depth = 0;
        $current = $path[0];

        for ($i = 1; $i < count($path); $i++) {
            $next = $path[$i];
            
            if ($next->parent_id == $current->id) {
                $depth--;
            } elseif ($current->parent_id == $next->id) {
                $depth++;
            }
            
            $current = $next;
        }

        return $depth;
    }
    

}
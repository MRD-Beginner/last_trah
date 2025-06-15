<?php
namespace App\Http\Controllers;

use App\Models\Anggota_Keluarga;
use App\Models\Trah;
use Illuminate\Http\Request;

class LogicController extends Controller
{
    

    
    public function compare(Request $request, $tree_id)
    {
        
        $anggota_keluarga = Anggota_Keluarga::where('tree_id', $tree_id)->get();

        $person1 = null;
        $person2 = null;
        $relationshipDetails = null;
        $relationshipDetailsReversed = null;
        $path = null;
        $pathRev = null;

        if ($request->has('compare') && $request->filled(['name1', 'name2'])) {
            $person1 = Anggota_Keluarga::where('nama', $request->name1)->where('tree_id', $tree_id)->first();
            $person2 = Anggota_Keluarga::where('nama', $request->name2)->where('tree_id', $tree_id)->first();

            if ($person1 && $person2) {
                // Person1 -> Person2
                //BFS
                // Mencari jalur dari Person 1 ke Person 2
                $path = $this->bfs($person1, $person2->id);
                $relationshipDetails = $path
                    ? $this->relationshipPath($path, $person1->nama, $person2->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];

                // Mencari jalur dari Person 2 ke Person 1
                $pathRev = $this->bfs($person2, $person1->id);
                $relationshipDetailsReversed = $pathRev
                    ? $this->relationshipPath($pathRev, $person2->nama, $person1->nama)
                    : ['relation' => 'Tidak ada hubungan yang ditemukan.', 'detailedPath' => []];

                //DFS
                // $visited = [];
                // $path = [];
                // $found = $this->dfs($person1, $person2->id, $visited, $path);
                // $relationshipDetails = $found
                //     ? $this->relationshipPath($path, $person1->nama, $person2->nama)
                //     : 'Tidak ada hubungan yang ditemukan.';

                // // Person2 -> Person1 (reversed)
                // $visitedRev = [];
                // $pathRev = [];
                // $foundRev = $this->dfs($person2, $person1->id, $visitedRev, $pathRev);
                // $relationshipDetailsReversed = $foundRev
                //     ? $this->relationshipPath($pathRev, $person2->nama, $person1->nama)
                //     : 'Tidak ada hubungan yang ditemukan.';
            }
        }
        return view('public_detail', [
            'tree_id'                      => $tree_id,
            'anggota_keluarga'            => $anggota_keluarga,
            'person1'                     => $person1,
            'person2'                     => $person2,
            'relationshipDetails'          => $relationshipDetails,
            'relationshipDetailsReversed'  => $relationshipDetailsReversed,
            'path'                         => $path,
            'pathRev'                      => $pathRev,
        ]);
    }


    // public function dfs($current, $targetId, &$visited, &$path)
    // {
    //     if (in_array($current->id, $visited)) return false;
    
    //     $visited[] = $current->id;
    //     $path[] = $current;
    
    //     if ($current->id == $targetId) return true;
    
    //     // Cek ke atas
    //     if ($current->parent) {
    //         if ($this->dfs($current->parent, $targetId, $visited, $path)) return true;
    //     }
    
    //     // Cek ke bawah
    //     foreach ($current->children as $child) {
    //         if ($this->dfs($child, $targetId, $visited, $path)) return true;
    //     }
    
    //     // Cek ke samping 
    //     if ($current->parent) {
    //         foreach ($current->parent->children as $sibling) {
    //             if ($sibling->id != $current->id) {
    //                 if ($this->dfs($sibling, $targetId, $visited, $path)) return true;
    //             }
    //         }
    //     }
    
    //     array_pop($path);
    //     return false;
    // }


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

    protected function calculateActualDepth($path)
    {
        if (count($path) < 2) return 0;

        $depth = 0;
        $current = $path[0];

        for ($i = 1; $i < count($path); $i++) {
            $next = $path[$i];
            
            if ($next->parent_id == $current->id) {
                $depth++;
            } elseif ($current->parent_id == $next->id) {
                $depth--;
            }
            
            $current = $next;
        }

        return $depth;
    }

    // HASIL HUBUNGAN
    public function relationshipResult($path) //ngaruh di hasil hubungan
    {
        $depth = $this->calculateActualDepth($path);
        $first = $path[0];
        $last = end($path);
        $jenis_kelamin = $first->jenis_kelamin; 
        
        $relations = [
            // Hubungan Vertikal
            1 => ['Laki-Laki' => 'adalah anak lanang dari', 'Perempuan' => 'adalah anak wedok dari'],
            -1 => ['Laki-Laki' => 'adalah bapak dari', 'Perempuan' => 'adalah ibuk dari'],
            2 => ['Laki-Laki' => 'adalah putu lanang dari', 'Perempuan' => 'adalah putu wedok dari'],
            -2 => ['Laki-Laki' => 'adalah eyang lanang dari', 'Perempuan' => 'adalah eyang wedok dari'],
            3 => ['Laki-Laki' => 'adalah cicit/buyut lanang dari',  'Perempuan' => 'adalah cicit/buyut wedok dari'],
            -3 => ['Laki-Laki' => 'adalah mbah buyut lanang dari', 'Perempuan' => 'adalah mbah buyut wedok dari'],
            4 => ['Laki-Laki' => 'adalah canggah lanang dari', 'Perempuan' => 'adalah canggah wedok dari'],
            -4 => ['Laki-Laki' => 'adalah mbah canggah lanang dari',  'Perempuan' => 'adalah mbah canggah wedok dari'],
            5 => ['Laki-Laki' => 'adalah wareg lanang dari', 'Perempuan' => 'adalah wareg wedok dari'],
            -5 => ['Laki-Laki' => 'adalah mbah wareg lanang dari', 'Perempuan' => 'adalah mbah wareg wedok dari'],
            6 => ['Laki-Laki' => 'adalah uthek-uthek lanang dari', 'Perempuan' => 'adalah uthek-uthek wedok dari'],
            -6 => ['Laki-Laki' => 'adalah mbah uthek-uthek lanang dari', 'Perempuan' => 'adalah mbah uthek-uthek wedok dari'],
            7 => ['Laki-Laki' => 'adalah gantung siwur lanang dari', 'Perempuan' => 'adalah gantung siwur wedok dari'],
            -7 => ['Laki-Laki' => 'adalah mbah gantung siwur lanang dari', 'Perempuan' => 'adalah mbah gantung siwur wedok dari'],
            8 => ['Laki-Laki' => 'adalah cicip moning lanang dari', 'Perempuan' => 'adalah cicip moning wedok dari'],
            -8 => ['Laki-Laki' => 'adalah mbah gropak santhe lanang dari', 'Perempuan' => 'adalah mbah gropak santhe wedok dari'],
            9 => ['Laki-Laki' => 'adalah petarang bobrok lanang dari', 'Perempuan' => 'adalah petarang bobrok wedok dari'],
            -9 => ['Laki-Laki' => 'adalah mbah debog bosok lanang dari', 'Perempuan' => 'adalah mbah debog bosok wedok dari'],
            10 => ['Laki-Laki' => 'adalah gropak santhe lanang dari', 'Perempuan' => 'adalah gropak santhe wedok dari'],
            -10 => ['Laki-Laki' => 'adalah mbah galih asem lanang dari', 'Perempuan' => 'adalah mbah galih asem wedok dari'],
            11 => ['Laki-Laki' => 'adalah gropak waton lanang dari', 'Perempuan' => 'adalah gropak waton wedok dari'],
            -11 => ['Laki-Laki' => 'adalah mbah gropak waton lanang dari', 'Perempuan' => 'adalah mbah gropak waton wedok dari'],
            12 => ['Laki-Laki' => 'adalah candheng lanang dari', 'Perempuan' => 'adalah candheng wedok dari'],
            -12 => ['Laki-Laki' => 'adalah mbah candheng lanang dari', 'Perempuan' => 'adalah mbah candheng wedok dari'],
            13 => ['Laki-Laki' => 'adalah giyeng lanang dari', 'Perempuan' => 'adalah giyeng wedok dari'],
            -13 => ['Laki-Laki' => 'adalah mbah giyeng lanang dari', 'Perempuan' => 'adalah mbah giyeng wedok dari'],
            14 => ['Laki-Laki' => 'adalah cumpleng lanang dari', 'Perempuan' => 'adalah cumpleng wedok dari'], 
            -14 => ['Laki-Laki' => 'adalah mbah cumpleng lanang dari', 'Perempuan' => 'adalah mbah cumpleng wedok dari'],
            15 => ['Laki-Laki' => 'adalah ampleng lanang dari', 'Perempuan' => 'adalah ampleng wedok dari'],
            -15 => ['Laki-Laki' => 'adalah mbah ampleng lanang dari', 'Perempuan' => 'adalah mbah ampleng wedok dari'],
            16 => ['Laki-Laki' => 'adalah menyaman lanang dari', 'Perempuan' => 'adalah menyaman wedok dari'],
            -16 => ['Laki-Laki' => 'adalah mbah menyaman lanang dari', 'Perempuan' => 'adalah mbah menyaman wedok dari'],
            17 => ['Laki-Laki' => 'adalah menya-menya lanang dari', 'Perempuan' => 'adalah menya-menya wedok dari'],
            -17 => ['Laki-Laki' => 'adalah mbah menya-menya lanang dari', 'Perempuan' => 'adalah mbah menya-menya wedok dari'],
            18 => ['Laki-Laki' => 'adalah trah tumerah lanang dari', 'Perempuan' => 'adalah trah tumerah wedok dari'],
            -18 => ['Laki-Laki' => 'adalah mbah trah tumerah lanang dari', 'Perempuan' => 'adalah mbah trah tumerah wedok dari'],

            // HUbungan Horizontal secara langsung
            'saudara_tua' => ['Laki-Laki' => 'adalah kangmas dari', 'Perempuan' => 'adalah mbakyu dari'],
            'saudara_muda' => ['Laki-Laki' => 'adalah adik lanang dari', 'Perempuan' => 'adalah adik wedok dari'],
            'nak-sanak' => ['Laki-Laki' => 'adalah sedulur nak-sanak lanang dengan', 'Perempuan' => 'adalah sedulur nak-sanak wedok dengan'],
            'misanan' => ['Laki-Laki' => 'adalah sedulur misanan lanang dengan',  'Perempuan' => 'adalah sedulur misanan wedok dengan'],
            'mindhoan' => ['Laki-Laki' => 'adalah sedulur mindhoan lanang dengan', 'Perempuan' => 'adalah sedulur mindhoan wedok dengan'],
            'pakde' => ['Laki-Laki' => 'adalah pakde dari',  'Perempuan' => 'adalah bude dari'],
            'paklek' => ['Laki-Laki' => 'adalah paklek dari', 'Perempuan' => 'adalah bulek dari'],
            'ponakan_prunan' => ['Laki-Laki' => 'adalah ponakan prunan lanang dari', 'Perempuan' => 'adalah ponakan prunan wedok dari'],
            'ponakan' => ['Laki-Laki' => 'adalah ponakan lanang dari','Perempuan' => 'adalah ponakan wedok dari'],

            // Hubungan Horizontal jauh
            'pakde_jauh' => ['Laki-Laki' => 'adalah Pakde Jauh dari', 'Perempuan' => 'adalah Bude Jauh dari'],
            'paklek_jauh' => ['Laki-Laki' => 'adalah Paklek Jauh dari', 'Perempuan' => 'adalah Bulek Jauh dari'],
            'keponakan_jauh' => ['Laki-Laki' => 'adalah ponakan lanang jauh dari', 'Perempuan' => 'adalah ponakan wedok jauh dari'],
        ];

        // 1. Hubungan Vertikal
        $isDirectLine = false;
        if ($depth < 0) { 
            $ancestor = $this->getAncestor($first, abs($depth));
            if ($ancestor && $ancestor->id === $last->id) $isDirectLine = true;
        } elseif ($depth > 0) { 
            $ancestor = $this->getAncestor($last, $depth);
            if ($ancestor && $ancestor->id === $first->id) $isDirectLine = true;
        }
        
        if ($isDirectLine) {
            $relationKey = 0;
            $genderKey = '';
            
            if ($depth < 0) { 
                $relationKey = abs($depth); 
                $genderKey = $first->jenis_kelamin; 
            } else { 
                $relationKey = -1 * $depth; 
                $genderKey = $first->jenis_kelamin; 
            }
            
            if (isset($relations[$relationKey][$genderKey])) {
                $relationText = $relations[$relationKey][$genderKey];
                return "{$first->nama} " . trim($relationText) . " {$last->nama}";
            }
        }   
        // 2. Hubungan Horizontal
        if ($depth === 0) {
            $p1 = optional($first->parent);
            $p2 = optional($last->parent);

            if ($p1 && $p2) {
                // 1. Saudara Kandung
                if ($p1->id === $p2->id) {
                    $key = $first->urutan < $last->urutan ? 'saudara_tua' : 'saudara_muda';
                    return "{$first->nama} " . $relations[$key][$jenis_kelamin] . " {$last->nama}";
                }
                // 2. Sepupu Pertama (Nak-sanak)
                $gp1 = optional($p1->parent);
                $gp2 = optional($p2->parent);
                if ($gp1 && $gp2 && $gp1->id === $gp2->id) {
                    return "{$first->nama} " . $relations['nak-sanak'][$jenis_kelamin] . " {$last->nama} (dari Eyang {$gp1->nama})";
                }
                // 3. Sepupu Kedua (Misanan)
                $ggp1 = optional($gp1)->parent;
                $ggp2 = optional($gp2)->parent;
                if ($ggp1 && $ggp2 && $ggp1->id === $ggp2->id) {
                    return "{$first->nama} " . $relations['misanan'][$jenis_kelamin] . " {$last->nama} (dari Buyut {$ggp1->nama})";
                }
                // 4. Sepupu Ketiga (Mindhoan)
                $gggp1 = optional($ggp1)->parent;
                $gggp2 = optional($ggp2)->parent;
                if ($gggp1 && $gggp2 && $gggp1->id === $gggp2->id) {
                    return "{$first->nama} " . $relations['mindhoan'][$jenis_kelamin] . " {$last->nama} (dari Canggah {$gggp1->nama})";
                }
            }
        }

         // 3. uncle nephew langsung
        if (abs($depth) === 1) {
            if ($depth === 1) {
                if ($first->parent_id && optional($last->parent)->parent_id && $first->parent_id === $last->parent->parent_id) {
                    $key = ($first->urutan < $last->parent->urutan) ? 'pakde' : 'paklek';
                    return "{$first->nama} " . $relations[$key][$first->jenis_kelamin] . " {$last->nama}";
                }
            } elseif ($depth === -1) {
                if (optional($first->parent)->parent_id && $last->parent_id && $first->parent->parent_id === $last->parent_id) {
                    $key = $last->urutan < $first->parent->urutan ? 'ponakan prunan' : 'ponakan';
                    return "{$first->nama} " . $relations[$key][$first->jenis_kelamin] . " {$last->nama}";
                }
            }
        }
        

        // 4a. uncle jauh
        if (abs($depth) === 1) {
            if ($depth === 1) { 
                $p1 = $first->parent; 
                $gp2 = optional($last->parent)->parent; 
                if ($p1 && $gp2 && $p1->parent_id === $gp2->parent_id) {
                    $key = ($p1->urutan > $gp2->urutan) ? 'paklek_jauh' : 'pakde_jauh';
                    return "{$first->nama} " . $relations[$key][$first->jenis_kelamin] . " {$last->nama}";
                }
            } elseif ($depth === -1) { 
                $gp1 = optional($first->parent)->parent; 
                $p2 = $last->parent; 
                if ($gp1 && $p2 && $gp1->parent_id === $p2->parent_id) {
                    return "{$first->nama} " . $relations['keponakan_jauh'][$first->jenis_kelamin] . " {$last->nama}";
                }
            }
        }
        // 4b. keturunan jauh
        if (abs($depth) >= 2) {
            if ($depth > 0) { 
                $level = $depth;
                $p1 = $first->parent; 
                $ancestorOfLast = $this->getAncestor($last, $level); 
                if ($p1 && $ancestorOfLast && $p1->id === $ancestorOfLast->parent_id) {
                    $baseRelationKey = -1 * $level; 
                    $baseRelationText = rtrim($relations[$baseRelationKey][$first->jenis_kelamin]);
                    $baseRelationText = str_replace(' dari', '', $baseRelationText); 
                    return "{$first->nama} adalah {$baseRelationText} jauh dari {$last->nama}";
                }
            } elseif ($depth < 0) { 
                $level = abs($depth);
                $ancestorOfFirst = $this->getAncestor($first, $level); 
                $p2 = $last->parent; 
                if ($ancestorOfFirst && $p2 && $ancestorOfFirst->parent_id === $p2->id) {
                    
                    $baseRelationKey = $level; 
                    if (isset($relations[$baseRelationKey][$first->jenis_kelamin])) {
                        $baseRelationText = rtrim($relations[$baseRelationKey][$first->jenis_kelamin]);
                        $baseRelationText = str_replace(' dari', '', $baseRelationText);

                        return "{$first->nama} adalah {$baseRelationText} jauh dari {$last->nama}";

                    }
                }
            
            }
        }   

        return "{$first->nama} dan {$last->nama} memiliki hubungan keluarga jauh";
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
                $relation = ($current->jenis_kelamin == 'Laki-Laki') ? "bapak " : "ibuk ";
                $detailedPath[] = " {$current->nama} {$relation} dari {$next->nama}";
                continue;
            }
            // 2. cek orang tua anak (reverse)
            elseif ($current->parent_id == $next->id) {
                $relation = ($current->jenis_kelamin == 'Laki-Laki') ? "anak lanang " : "anak wedok ";
                $detailedPath[] = " {$current->nama} {$relation}ke-{$current->urutan} dari {$next->nama}";
                continue;
            }
         
        }

        return [
            'relation' => " {$relationshipDescription} ",
            'detailedPath' => $detailedPath
        ];
    }

    
    

}
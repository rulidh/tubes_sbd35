<?php
    
namespace App\Http\Controllers;
    
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
    
class PemilikController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:pemilik-list|pemilik-create|pemilik-edit|pemilik-delete', ['only' => ['index','show']]);
         $this->middleware('permission:pemilik-create', ['only' => ['create','store']]);
         $this->middleware('permission:pemilik-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:pemilik-delete', ['only' => ['destroy','deletelist']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $pemiliks = DB::table('pemilik')
                    ->Where('nama','LIKE','%'.$keyword.'%')
                    ->whereNull('deleted_at')
                    ->paginate(6);
        return view('pemilik.index',compact('pemiliks'))
                ->with('i', (request()->input('page', 1) - 1) * 6);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pemilik.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'ktp_pemilik' => 'required',
            'nama' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required'
        ]);
    
        DB::insert('INSERT INTO pemilik(ktp_pemilik,nama,no_hp,alamat) VALUES (:ktp_pemilik, :nama,:no_hp ,:alamat)',
        [
            'ktp_pemilik' => $request->ktp_pemilik,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ]
        );
    
        return redirect()->route('pemilik.index')
                        ->with('Berhasil','Menambahkan Data Pemilik');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function show(Pemilik $pemilik)
    {
        return view('pemilik.show',compact('pemiliks'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pemilik = DB::table('pemilik')->where('ktp_pemilik', $id)->first();
        return view('pemilik.edit',compact('pemilik'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
         $request->validate([
            'ktp_pemilik' => 'required',
            'nama' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required'
        ]);
       //$game->update($request->all());
        DB::update('UPDATE pemilik SET ktp_pemilik = :ktp_pemilik, nama = :nama,no_hp = :no_hp ,alamat = :alamat WHERE ktp_pemilik = :id',
        [
            'id' => $id,
            'ktp_pemilik' => $request->ktp_pemilik,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
           
        ]
        );
        return redirect()->route('pemilik.index')
                        ->with('Berhasil','Mengubah Data Pemilik');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pemilik  $pemilik
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::update('UPDATE pemilik SET deleted_at = NOW() WHERE ktp_pemilik = :ktp_pemilik', ['ktp_pemilik' => $id]);
    
        return redirect()->route('pemilik.index')
                        ->with('Berhasil','Menghapus Data Pemilik');
    }
    
    public function deletelist()
    {
        $pemiliks = DB::table('pemilik')
                    ->whereNotNull('deleted_at')
                    ->paginate(6);
        return view('/pemilik/trash',compact('pemiliks'))
            ->with('i', (request()->input('page', 1) - 1) * 6);

    }
    
    public function restore($id)
    {
        DB::update('UPDATE pemilik SET deleted_at = NULL WHERE ktp_pemilik = :ktp_pemilik', ['ktp_pemilik' => $id]);
        return redirect()->route('pemilik.index')
                        ->with('Berhasil','Memulihkan Data Pemilik');
    }

    public function deleteforce($id)
    {
        DB::delete('DELETE FROM pemilik WHERE ktp_pemilik=:ktp_pemilik', ['ktp_pemilik' => $id]);
        return redirect()->route('pemilik.index')
                        ->with('Berhasil','Menghapus Permanen Data Pemilik');
    }
}
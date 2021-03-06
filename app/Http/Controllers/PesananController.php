<?php

namespace App\Http\Controllers;

use App\Barang;
use Illuminate\Http\Request;

use Auth;
use DB;
use DateTime;
use App\PesananBarang;
use App\BarangTerpesan;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PesananController extends Controller
{

    protected $pesananBarang;
    protected $barangPesanan;
    protected $barang;


    public function __construct(PesananBarang $pesanan, Barang $barang, BarangTerpesan $barangPesanan)
    {
        $this->pesananBarang = $pesanan;
        $this->barangPesanan = $barangPesanan;
        $this->barang = $barang;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $data = PesananBarang::all();
        $pesananuser = DB::table('pesanan_barangs')->where('pemesan','=',Auth::user()->nama)->get();
//        dd($pesananuser);
        return view('aktivitas.pesanan.index', compact('data','pesananuser'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $result = Barang::all();
        if(!$result->isEmpty())
        {
            $id = $this->pesananBarang->max('id');
            $id = $id === null ? 1 : $id + 1;
            $tanggal = (new DateTime);
            $tanggal = $tanggal->format('Y-m-d');
            $data = Barang::all();
            return view('aktivitas.pesanan.create', compact('data', 'id', 'tanggal'));
        }
        else{
            return redirect()->back()->withErrors(['Data barang kosong, silahkan mengisi Form Pengajuan Barang terlebih dahulu!']);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $all = $request->all();
//         dd($all);

        $pesananFill = $this->pesananBarang->getFillable();
        $pesanan = $this->pesananBarang->Create($request->only($pesananFill));

//         dd($all['barpes']);
        foreach($all['barpes'] as $barpes){
            if(!is_numeric($barpes['kuantitas']))
            {
                return redirect()->back()->withErrors(['Kuantitas harus berupa angka']);
            }
            elseif($barpes['barang_id'] == null)
            {
                return redirect()->back()->withErrors(['Kode barang tidak boleh kosong']);
            }
            else
            {
                $barangTerpakai = Barang::find($barpes['barang_id']);
                $barangTerpakai->used = '1';
                $barangTerpakai->save();
                $barang_id = array_pull($barpes, 'barang_id');
//                dd($barang_id);
                $pesanan->barangs()->attach($barang_id, $barpes);
            }
        }
        return redirect('pesanan/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $pesananBarang=$this->pesananBarang->find($id);
//        dd($pesananBarang->barangs);
        return view('aktivitas/pesanan/show',compact('pesananBarang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function diterima($id)
    {
//        dd($id);
        $list = $this->barangPesanan->all()->where('pesanan_barang_id',intval($id));
        $length = sizeof($list);
        $checklist = 0;
//        dd($list, $length);
        foreach($list as $data)
        {
            $check = Barang::find($data['barang_id']);
//            dd($check['kuantitas']+$check['pengadaan']-$check['pemakaian'],$list,$data['kuantitas']);
                if($check['kuantitas']+$check['pengadaan']-$check['pemakaian'] >= $data['kuantitas'])
                $checklist++;
        }
//        dd($checklist, $length);
        if($checklist == $length)
        {
            foreach($list as $data)
            {
                $check = Barang::find($data['barang_id']);
//                $check['kuantitas'] -= $data['kuantitas'];
                $check['pemakaian'] += $data['kuantitas'];
                $check->save();
            }
            $pesanan = PesananBarang::find($id);
            $pesanan->status = 'Diterima';
            $pesanan->save();
            return redirect('pesanan/index');
        }
        else
        {
            $pesanan = PesananBarang::find($id);
            $pesanan->status = 'Harap Menunggu';
            $pesanan->save();
            return redirect('pesanan/index')->withErrors(['Terdapat barang yang belum tersedia, lakukan Pengadaan Barang!']);
        }
    }

    public function menunggu($id)
    {
        $pesanan = PesananBarang::find($id);
        $pesanan->status = 'Harap Menunggu';
        $pesanan->save();
        return redirect('pesanan/index');
    }

    public function ditolak($id)
    {
        $pesanan = PesananBarang::find($id);
        $pesanan->status = 'Ditolak';
        $pesanan->save();
        return redirect('pesanan/index');
    }

    public function keterangan(Request $request, $id)
    {
        $all = $request->all();
        $pesanan = PesananBarang::find($id);
        $pesanan->keterangan = $all['keterangan'];
        $pesanan->save();
        return redirect('pesanan/index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

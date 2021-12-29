<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstWarna;
use App\Models\MstUkuran;
use App\Models\Produk;
use DB;
use Validator;

class ProdukController extends BaseController
{
    protected $pages = 'pages.produk';

    public function __construct(){

        parent::__construct();
    }

    public function index()
    {
        try{
            $Searchkeyword = $this->request->input('Searchkeyword');

            $this->data = [
                'title' => 'Produk',
                'list_ukuran' => 'Produk',
                'list_warna' => 'Produk',
                'TableList' => $this->pages.'.table'
            ];

            $datatable = Produk::join('mst_ukuran',function($join) {
                                    $join->on('produk_.kode_ukuran','=','mst_ukuran.kode_ukuran');
                                })
                                ->join('mst_warna',function($join) {
                                    $join->on('produk_.kode_warna','=','mst_warna.kode_warna');
                                });

            $datatable->when(!empty(isset($Searchkeyword)), function ($query) use ($Searchkeyword) {
                $query->where('produk_.nama_barang', 'like', '%'.$Searchkeyword.'%')
                        ->orWhere('mst_ukuran.ukuran', 'like', '%'.$Searchkeyword.'%')
                        ->orWhere('mst_warna.nama_warna', 'like', '%'.$Searchkeyword.'%');
            });

            $sql  = $datatable->select(
                                    'produk_.*',
                                    'mst_ukuran.ukuran',
                                    'mst_warna.nama_warna'
                                )
                                ->orderBy('produk_.kode_barang', 'DESC');

            $this->data['datatableCount'] = $sql->count();
            $datatable  = $datatable->paginate(10);

            if($this->request->ajax()){
                return view($this->data['TableList'],compact('datatable'))->with($this->data);
            }

            return view($this->pages.'.index',compact('datatable'))->with($this->data);
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public function ActionAjax()
    {
        try{

            $transaction = DB::transaction(function () {

                $data = array();

                if(!$this->request->ajax())
                {
                    $data = [
                        'respon' => 'failed',
                        'message' => "You don't have access"
                    ];
                }
                else
                {
                    $module = $this->request->input('module');
                    $act = $this->request->input('act');

                    if($module == "produk" && $act == "add")
                    {
                        $validasi = $this->request->validate([
                            'v_nama_barang' => 'required',
                            'v_kode_ukuran' => 'required',
                            'v_kode_warna' => 'required',
                            'v_harga_dasar' => 'required|min:1|not_in:0',
                            'v_harga' => 'required|min:1|not_in:0'
                        ]);

                        $nama_barang = $this->request->input('v_nama_barang');
                        $kode_ukuran = $this->request->input('v_kode_ukuran');
                        $kode_warna = $this->request->input('v_kode_warna');
                        $harga_dasar = \Common::SaveInt($this->request->input('v_harga_dasar'));
                        $harga = \Common::SaveInt($this->request->input('v_harga'));

                        $isExist = Produk::where('nama_barang', $nama_barang)
                                            ->where('kode_ukuran',$kode_ukuran)
                                            ->where('kode_warna',$kode_warna)
                                            ->select('kode_barang')
                                            ->exists();

                        if($isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Produk already exist'
                            ];
                        }
                        if($harga_dasar*1>$harga*1)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Harga harus lebih besar dari harga dasar'
                            ];
                        }
                        else
                        {
                            $insert = new Produk();
                            $insert->nama_barang = $nama_barang;
                            $insert->kode_ukuran = $kode_ukuran;
                            $insert->kode_warna = $kode_warna;
                            $insert->harga = $harga;
                            $insert->harga_dasar = $harga_dasar;
                            $insert->save();

                            $data = [
                                'respon' => 'success',
                                'message' => 'Produk successfully insert',
                                'url' => route('produk'),
                            ];
                        }
                    }

                    else if($module == "produk" && $act == "modalEdit")
                    {
                        $this->request->validate([
                            'id' => 'required'
                        ]);

                        $id = \Common::hashids()->decode($this->request->input('id'));

                        $isExist = Produk::where('kode_barang', $id)->select('kode_barang')->exists();

                        if(!$isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Produk not exist'
                            ];
                        }
                        else
                        {
                            $data_produk = Produk::where('kode_barang', $id)->first();
                            $list_ukuran = MstUkuran::orderBy('ukuran', 'asc')->get();
                            $list_warna = MstWarna::orderBy('nama_warna', 'asc')->get();

                            $data = [
                                'respon' => 'success',
                                'data' => $data_produk,
                                'list_ukuran' => $list_ukuran,
                                'list_warna' => $list_warna
                            ];
                        }
                    }

                    else if($module == "produk" && $act == "edit")
                    {
                        $this->request->validate([
                            'kode_barang' => 'required',
                            'v_nama_barang_old' => 'required',
                            'v_nama_barang' => 'required',
                            'v_kode_ukuran' => 'required',
                            'v_kode_warna' => 'required',
                            'v_harga_dasar' => 'required|min:1|not_in:0',
                            'v_harga' => 'required|min:1|not_in:0'
                        ]);

                        $kode_barang = \Common::hashids()->decode($this->request->input('kode_barang'));

                        $nama_barang_old = $this->request->input('v_nama_barang_old');
                        $nama_barang = $this->request->input('v_nama_barang');
                        $kode_ukuran = $this->request->input('v_kode_ukuran');
                        $kode_warna = $this->request->input('v_kode_warna');
                        $harga_dasar = \Common::SaveInt($this->request->input('v_harga_dasar'));
                        $harga = \Common::SaveInt($this->request->input('v_harga'));

                        $isExist = Produk::where('kode_barang', $kode_barang)->select('kode_barang')->exists();

                        $isExist2 = false;
                        if(strtoupper($nama_barang_old)!=strtoupper($nama_barang))
                        {
                            $isExist2 = Produk::where('nama_barang', $nama_barang)
                                                ->where('kode_ukuran',$kode_ukuran)
                                                ->where('kode_warna',$kode_warna)
                                                ->select('kode_barang')
                                                ->exists();
                        }

                        if(!$isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Barang not exist'
                            ];
                        }
                        else if($isExist2)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Barang name already exist'
                            ];
                        }
                        if($harga_dasar*1>$harga*1)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Harga harus lebih besar dari harga dasar'
                            ];
                        }
                        else
                        {
                            $insert = Produk::where('kode_barang',$kode_barang)->firstOrFail();
                            $insert->nama_barang = $nama_barang;
                            $insert->kode_ukuran = $kode_ukuran;
                            $insert->kode_warna = $kode_warna;
                            $insert->harga = $harga;
                            $insert->harga_dasar = $harga_dasar;
                            $insert->update();

                            $data = [
                                'respon' => 'success',
                                'message' => 'Barang Warna successfully update',
                                'url' => route('produk'),
                            ];
                        }
                    }

                    else if($module == "produk" && $act == "delete")
                    {
                        $this->request->validate([
                            'id' => 'required',
                        ]);

                        $kode_barang = \Common::hashids()->decode($this->request->input('id'));

                        $isExist = Produk::where('kode_barang', $kode_barang)->select('kode_barang')->exists();

                        if(!$isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Barang not exist'
                            ];
                        }
                        else
                        {
                            Produk::where('kode_barang', $kode_barang)->delete();

                            $data = [
                                'respon' => 'success',
                                'message' => 'Barang successfully delete',
                                'url' => route('produk'),
                            ];
                        }
                    }

                    else if($module == "produk" && $act == "getDataOption")
                    {
                        $list_ukuran = MstUkuran::orderBy('ukuran', 'asc')->get();
                        $list_warna = MstWarna::orderBy('nama_warna', 'asc')->get();

                        $data = [
                            'list_ukuran' => $list_ukuran,
                            'list_warna' => $list_warna
                        ];
                    }
                }

                return response()->json($data, 200);

            });

            return $transaction;
        }
        catch (\Illuminate\Database\QueryException $e) {

            $act = $this->request->input('act');

            $data = [
                'respon' => 'failed',
                'message' => 'Failed to '.$e.' Master Warna',
            ];

            return response()->json($data, 200);
        }
        catch(Exception $e){
            DB::rollback();
            throw $e;
        }
    }
}

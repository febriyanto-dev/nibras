<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstWarna;
use DB;
use Validator;

class WarnaController extends BaseController
{
    protected $pages = 'pages.mst_warna';

    public function __construct(){

        parent::__construct();
    }

    public function index()
    {
        try{
            $Searchkeyword = $this->request->input('Searchkeyword');

            $this->data = [
                'title' => 'Master Warna',
                'TableList' => $this->pages.'.table'
            ];

            $datatable = MstWarna::query();

            $datatable->when(!empty(isset($Searchkeyword)), function ($query) use ($Searchkeyword) {
                $query->where('nama_warna', 'like', '%'.$Searchkeyword.'%');
            });


            $sql  = $datatable->orderBy('kode_warna', 'DESC');
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

                    if($module == "master_warna" && $act == "add")
                    {
                        $validasi = $this->request->validate([
                            'v_warna' => 'required'
                        ]);

                        $warna = $this->request->input('v_warna');

                        $isExist = MstWarna::where('nama_warna', $warna)->select('kode_warna')->exists();

                        if($isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Warna already exist'
                            ];
                        }
                        else
                        {
                            $insert = new MstWarna();
                            $insert->nama_warna = $warna;
                            $insert->save();

                            $data = [
                                'respon' => 'success',
                                'message' => 'Master Warna successfully insert',
                                'url' => route('warna'),
                            ];
                        }
                    }

                    else if($module == "master_warna" && $act == "modalEdit")
                    {
                        $this->request->validate([
                            'id' => 'required'
                        ]);

                        $id = \Common::hashids()->decode($this->request->input('id'));

                        $isExist = MstWarna::where('kode_warna', $id)->select('kode_warna')->exists();

                        if(!$isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Warna not exist'
                            ];
                        }
                        else
                        {
                            $data_warna = MstWarna::where('kode_warna', $id)->first();

                            $data = [
                                'respon' => 'success',
                                'data' => $data_warna
                            ];
                        }
                    }

                    else if($module == "master_warna" && $act == "edit")
                    {
                        $this->request->validate([
                            'kode_warna' => 'required',
                            'v_warna' => 'required',
                            'v_warna_old' => 'required'
                        ]);

                        $kode_warna = \Common::hashids()->decode($this->request->input('kode_warna'));
                        $warna = $this->request->input('v_warna');
                        $v_warna_old = $this->request->input('v_warna_old');

                        $isExist = MstWarna::where('kode_warna', $kode_warna)->select('kode_warna')->exists();

                        $isExist2 = false;
                        if(strtoupper($warna)!=strtoupper($v_warna_old))
                        {
                            $isExist2 = MstWarna::where('nama_warna', $warna)->select('nama_warna')->exists();
                        }

                        if(!$isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Warna not exist'
                            ];
                        }
                        else if($isExist2)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Warna name already exist'
                            ];
                        }
                        else
                        {
                            $insert = MstWarna::where('kode_warna',$kode_warna)->firstOrFail();
                            $insert->nama_warna = $warna;
                            $insert->update();

                            $data = [
                                'respon' => 'success',
                                'message' => 'Master Warna successfully update',
                                'url' => route('warna'),
                            ];
                        }
                    }

                    else if($module == "master_warna" && $act == "delete")
                    {
                        $this->request->validate([
                            'id' => 'required',
                        ]);

                        $kode_warna = \Common::hashids()->decode($this->request->input('id'));

                        $isExist = MstWarna::where('kode_warna', $kode_warna)->select('kode_warna')->exists();

                        if(!$isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Warna not exist'
                            ];
                        }
                        else
                        {
                            MstWarna::where('kode_warna', $kode_warna)->delete();

                            $data = [
                                'respon' => 'success',
                                'message' => 'Master Warna successfully delete',
                                'url' => route('warna'),
                            ];
                        }
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

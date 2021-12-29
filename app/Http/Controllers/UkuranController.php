<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MstUkuran;
use DB;
use Validator;

class UkuranController extends BaseController
{
    protected $pages = 'pages.mst_ukuran';

    public function __construct(){

        parent::__construct();
    }

    public function index()
    {
        try{
            $Searchkeyword = $this->request->input('Searchkeyword');

            $this->data = [
                'title' => 'Master Ukuran',
                'TableList' => $this->pages.'.table'
            ];

            $datatable = MstUkuran::query();

            $datatable->when(!empty(isset($Searchkeyword)), function ($query) use ($Searchkeyword) {
                        $query->where('ukuran', 'like', '%'.$Searchkeyword.'%');
            });


            $sql  = $datatable->orderBy('kode_ukuran', 'DESC');
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

                    if($module == "master_ukuran" && $act == "add")
                    {
                        $validasi = $this->request->validate([
                            'v_ukuran' => 'required'
                        ]);

                        $ukuran = $this->request->input('v_ukuran');

                        $isExist = MstUkuran::where('ukuran', $ukuran)->select('kode_ukuran')->exists();

                        if($isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'flag' => 'invalid',
                                'message' => 'Ukuran already exist'
                            ];
                        }
                        else
                        {
                            $insert = new MstUkuran();
                            $insert->ukuran = $ukuran;
                            $insert->save();

                            $data = [
                                'respon' => 'success',
                                'message' => 'Master Ukuran successfully insert',
                                'url' => route('ukuran'),
                            ];
                        }
                    }

                    else if($module == "master_ukuran" && $act == "modalEdit")
                    {
                        $this->request->validate([
                            'id' => 'required'
                        ]);

                        $id = \Common::hashids()->decode($this->request->input('id'));

                        $isExist = MstUkuran::where('kode_ukuran', $id)->select('kode_ukuran')->exists();

                        if(!$isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Ukuran not exist'
                            ];
                        }
                        else
                        {
                            $data_ukuran = MstUkuran::where('kode_ukuran', $id)->first();

                            $data = [
                                'respon' => 'success',
                                'data' => $data_ukuran
                            ];
                        }
                    }

                    else if($module == "master_ukuran" && $act == "edit")
                    {
                        $this->request->validate([
                            'kode_ukuran' => 'required',
                            'v_ukuran' => 'required',
                            'v_ukuran_old' => 'required'
                        ]);

                        $kode_ukuran = \Common::hashids()->decode($this->request->input('kode_ukuran'));
                        $ukuran = $this->request->input('v_ukuran');
                        $v_ukuran_old = $this->request->input('v_ukuran_old');

                        $isExist = MstUkuran::where('kode_ukuran', $kode_ukuran)->select('kode_ukuran')->exists();

                        $isExist2 = false;
                        if(strtoupper($ukuran)!=strtoupper($v_ukuran_old))
                        {
                            $isExist2 = MstUkuran::where('ukuran', $ukuran)->select('ukuran')->exists();
                        }

                        if(!$isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Ukuran not exist'
                            ];
                        }
                        else if($isExist2)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Ukuran name already exist'
                            ];
                        }
                        else
                        {
                            $insert = MstUkuran::where('kode_ukuran',$kode_ukuran)->firstOrFail();
                            $insert->ukuran = $ukuran;
                            $insert->update();

                            $data = [
                                'respon' => 'success',
                                'message' => 'Master Ukuran successfully update',
                                'url' => route('ukuran'),
                            ];
                        }
                    }

                    else if($module == "master_ukuran" && $act == "delete")
                    {
                        $this->request->validate([
                            'id' => 'required',
                        ]);

                        $kode_ukuran = \Common::hashids()->decode($this->request->input('id'));

                        $isExist = MstUkuran::where('kode_ukuran', $kode_ukuran)->select('kode_ukuran')->exists();

                        if(!$isExist)
                        {
                            $data = [
                                'respon' => 'failed',
                                'message' => 'Ukuran not exist'
                            ];
                        }
                        else
                        {
                            MstUkuran::where('kode_ukuran', $kode_ukuran)->delete();

                            $data = [
                                'respon' => 'success',
                                'message' => 'Master Ukuran successfully delete',
                                'url' => route('ukuran'),
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
                'message' => 'Failed to '.$e.' Master Ukuran',
            ];

            return response()->json($data, 200);
        }
        catch(Exception $e){
            DB::rollback();
            throw $e;
        }
    }
}

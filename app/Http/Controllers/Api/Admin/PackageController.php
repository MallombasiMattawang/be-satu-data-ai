<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get datas
        $datas = Package::when(request()->search, function ($datas) {
            $datas = $datas->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        //append query string to pagination links
        $datas->appends(['search' => request()->search]);

        //return with Api Resource
        return new PackageResource(true, 'List Data', $datas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Validate request
         */
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'description'   => 'required',
            'price'   => 'required',
            'speed'   => 'required',
            'quota'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create data
        $data = Package::create(
            [
                'name'          => $request->name,
                'description'   => $request->description,
                'price'   => $request->price,
                'speed'   => $request->speed,
                'quota'   => $request->quota,
            ]
        );

        if ($data) {
            //return success with Api Resource
            return new PackageResource(true, 'Data Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new PackageResource(false, 'Data Gagal Disimpan!', null);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get data
        $data = Package::findOrFail($id);

        if ($data) {
            //return success with Api Resource
            return new PackageResource(true, 'Detail Data!', $data);
        }

        //return failed with Api Resource
        return new PackageResource(false, 'Detail Data Tidak Ditemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * validate request
         */
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'description'   => 'required',
            'price'   => 'required',
            'speed'   => 'required',
            'quota'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find data by ID
        $data = Package::findOrFail($id);

        //update data
        $data->update([
            'name'          => $request->name,
            'description'   => $request->description,
            'price'   => $request->price,
            'speed'   => $request->speed,
            'quota'   => $request->quota,
        ]);

        if ($data) {
            //return success with Api Resource
            return new PackageResource(true, 'Data Berhasil Diupdate!', $data);
        }

        //return failed with Api Resource
        return new PackageResource(false, 'Data Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find data by ID
        $data = Package::findOrFail($id);

        //delete data
        if ($data->delete()) {
            //return success with Api Resource
            return new PackageResource(true, 'Data Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new PackageResource(false, 'Data Gagal Dihapus!', null);
    }
}

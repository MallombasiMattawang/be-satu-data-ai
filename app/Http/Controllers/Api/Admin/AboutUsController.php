<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutUsResource;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get datas
        $datas = AboutUs::when(request()->search, function ($datas) {
            $datas = $datas->where('section', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        //append query string to pagination links
        $datas->appends(['search' => request()->search]);

        //return with Api Resource
        return new AboutUsResource(true, 'List Data', $datas);
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
            'section'          => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create data
        $data = AboutUs::create(
            [
                'section' => $request->section,
                'content' => $request->content,

            ]
        );

        if ($data) {
            //return success with Api Resource
            return new AboutUsResource(true, 'Data Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new AboutUsResource(false, 'Data Gagal Disimpan!', null);
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
        $data = AboutUs::findOrFail($id);

        if ($data) {
            //return success with Api Resource
            return new AboutUsResource(true, 'Detail Data!', $data);
        }

        //return failed with Api Resource
        return new AboutUsResource(false, 'Detail Data Tidak Ditemukan!', null);
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
            'section'   => 'required',
            'content'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find data by ID
        $data = AboutUs::findOrFail($id);

        //update data
        $data->update([
            'section' => $request->section,
            'content' => $request->content,
        ]);

        if ($data) {
            //return success with Api Resource
            return new AboutUsResource(true, 'Data Berhasil Diupdate!', $data);
        }

        //return failed with Api Resource
        return new AboutUsResource(false, 'Data Gagal Diupdate!', null);
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
        $data = AboutUs::findOrFail($id);

        //delete data
        if($data->delete()) {
            //return success with Api Resource
            return new AboutUsResource(true, 'Data Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new AboutUsResource(false, 'Data Gagal Dihapus!', null);
    }

}

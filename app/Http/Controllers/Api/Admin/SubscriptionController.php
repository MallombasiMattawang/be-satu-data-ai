<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get datas
        $datas = Subscription::when(request()->search, function ($datas) {
            $datas = $datas->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        //append query string to pagination links
        $datas->appends(['search' => request()->search]);

        //return with Api Resource
        return new SubscriptionResource(true, 'List Data', $datas);
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
        $data = Subscription::create(
            [
                'section' => $request->section,
                'content' => $request->content,

            ]
        );

        if ($data) {
            //return success with Api Resource
            return new SubscriptionResource(true, 'Data Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new SubscriptionResource(false, 'Data Gagal Disimpan!', null);
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
        $data = Subscription::findOrFail($id);

        if ($data) {
            //return success with Api Resource
            return new SubscriptionResource(true, 'Detail Data!', $data);
        }

        //return failed with Api Resource
        return new SubscriptionResource(false, 'Detail Data Tidak Ditemukan!', null);
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
        $data = Subscription::findOrFail($id);

        //update data
        $data->update([
            'section' => $request->section,
            'content' => $request->content,
        ]);

        if ($data) {
            //return success with Api Resource
            return new SubscriptionResource(true, 'Data Berhasil Diupdate!', $data);
        }

        //return failed with Api Resource
        return new SubscriptionResource(false, 'Data Gagal Diupdate!', null);
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
        $data = Subscription::findOrFail($id);

        //delete data
        if($data->delete()) {
            //return success with Api Resource
            return new SubscriptionResource(true, 'Data Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new SubscriptionResource(false, 'Data Gagal Dihapus!', null);
    }

}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get datas
        $datas = Partner::when(request()->search, function ($datas) {
            $datas = $datas->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        //append query string to pagination links
        $datas->appends(['search' => request()->search]);

        //return with Api Resource
        return new PartnerResource(true, 'List Data', $datas);
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
            'logo_url'   => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'website'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload logo_url
        $logo_url = $request->file('logo_url');
        // $image->storeAs('public/blogs', $image->hashName());
        $path = $logo_url->storeAs('public/logo_url', $logo_url->hashName());
        // dd(storage_path('app/public'), $path);

        //create data
        $data = Partner::create(
            [
                'name'          => $request->name,
                'logo_url'   => $logo_url->hashName(),
                'website'   => $request->website,

            ]
        );

        if ($data) {
            //return success with Api Resource
            return new PartnerResource(true, 'Data Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new PartnerResource(false, 'Data Gagal Disimpan!', null);
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
        $data = Partner::findOrFail($id);

        if ($data) {
            //return success with Api Resource
            return new PartnerResource(true, 'Detail Data!', $data);
        }

        //return failed with Api Resource
        return new PartnerResource(false, 'Detail Data Tidak Ditemukan!', null);
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
            'logo_url'   => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
            'website'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find data by ID
        $data = Partner::findOrFail($id);

        //check image update
        if ($request->file('logo_url')) {
            //remove old image
            Storage::disk('local')->delete('public/logo_url/' . basename($data->image_url));

            //upload new image
            $logo_url = $request->file('logo_url');
            // $image->storeAs('public/blogs', $image->hashName());
            $path = $logo_url->storeAs('public/logo_url', $logo_url->hashName());

            //update data
            $data->update([
                'name'          => $request->name,
                'logo_url'   => $logo_url->hashName(),
                'website'   => $request->website,
            ]);
        }

        //update data
        $data->update([
            'name'          => $request->name,
            'website'   => $request->website,
        ]);

        if ($data) {
            //return success with Api Resource
            return new PartnerResource(true, 'Data Berhasil Diupdate!', $data);
        }

        //return failed with Api Resource
        return new PartnerResource(false, 'Data Gagal Diupdate!', null);
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
        $data = Partner::findOrFail($id);

        //delete data
        if ($data->delete()) {
            //return success with Api Resource
            return new PartnerResource(true, 'Data Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new PartnerResource(false, 'Data Gagal Dihapus!', null);
    }
}

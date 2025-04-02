<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PromotionResource;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get datas
        $datas = Promotion::when(request()->search, function ($datas) {
            $datas = $datas->where('title', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        //append query string to pagination links
        $datas->appends(['search' => request()->search]);

        //return with Api Resource
        return new PromotionResource(true, 'List Data', $datas);
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
            'title' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'image_url' => 'required|image|mimes:jpeg,jpg,png|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image_url');
        // $image->storeAs('public/blogs', $image->hashName());
        $path = $image->storeAs('public/promotions', $image->hashName());
        // dd(storage_path('app/public'), $path);

        //create data
        $data = Promotion::create(
            [
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'image_url'       => $image->hashName(),

            ]
        );

        if ($data) {
            //return success with Api Resource
            return new PromotionResource(true, 'Data Berhasil Disimpan!', $data);
        }

        //return failed with Api Resource
        return new PromotionResource(false, 'Data Gagal Disimpan!', null);
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
        $data = Promotion::findOrFail($id);

        if ($data) {
            //return success with Api Resource
            return new PromotionResource(true, 'Detail Data!', $data);
        }

        //return failed with Api Resource
        return new PromotionResource(false, 'Detail Data Tidak Ditemukan!', null);
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
            'title' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'image_url' => 'nullable|image|mimes:jpeg,jpg,png|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find data by ID
        $data = Promotion::findOrFail($id);

        //check image update
        if ($request->file('image_rl')) {
            //remove old image
            Storage::disk('local')->delete('public/blogs/' . basename($data->image_url));

            //upload new image
            $image = $request->file('image_url');
            // $image->storeAs('public/blogs', $image->hashName());
            $path = $image->storeAs('public/blogs', $image->hashName());

            //update data
            $data->update([
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'image_url'       => $image->hashName(),
            ]);
        }

        //update data
        $data->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        if ($data) {
            //return success with Api Resource
            return new PromotionResource(true, 'Data Berhasil Diupdate!', $data);
        }

        //return failed with Api Resource
        return new PromotionResource(false, 'Data Gagal Diupdate!', null);
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
        $data = Promotion::findOrFail($id);

        //delete data
        if ($data->delete()) {
            //return success with Api Resource
            return new PromotionResource(true, 'Data Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new PromotionResource(false, 'Data Gagal Dihapus!', null);
    }
}

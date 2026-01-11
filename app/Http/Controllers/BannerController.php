<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()  
    {
        try {

            $banners = Banner::all();
            return response()->json($banners);

        } catch (Exception $e) {
            // Writing detailed error message to the developer log and 
            // returning a simple error message to the client
            Log::error('Error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Oops! Something went wrong.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:10',
            'file' => 'required',
            // 'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url' => 'required|url'
        ]);
        
        try {

            if ($request->has('file')) {
                // Extract file from the API call/request
                $file = $request->file('file');

                $existing_banner = Banner::where('position', $request->position)->first();
                if ($existing_banner) {
                    return response()->json(['message' => "{$existing_banner->name} already exists"]);
                }

                $fileName = time().'_'.$file->getClientOriginalName();
                $file->move(\public_path('banners'), $fileName);

                $banner = new Banner([
                    'name' => $request->name,
                    'position' => $request->position,
                    'filename' => $fileName,
                    'url' => $request->url
                ]);
                $banner->save();
            }

            return response()->json(['message' => 'Banner added.'], 201);

        } catch (Exception $e) {
            // Writing detailed error message to the developer log and 
            // returning a simple error message to the client
            Log::error('Error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Oops! Something went wrong.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $banner = Banner::findOrFail($id);
            return response()->json($banner);

        } catch (\Exception $e) {
            // Writing detailed error message to the developer log and 
            // returning a simple error message to the client
            Log::error('Error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Oops! Something went wrong.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $banner = Banner::findOrFail($id);
            if (file_exists("banners/{$banner->filename}")) {
                unlink("banners/{$banner->filename}");
            }
            // $res = Storage::delete("banners/{$banner->filename}");
            // var_dump($res);
            $banner->delete();
            return response()->json(['success' => true, 'message' => 'Banner deleted.'], 200);

        } catch (Exception $e) {
            // Writing detailed error message to the developer log and 
            // returning a simple error message to the client
            Log::error('Error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Oops! Something went wrong.'], 500);
        }
    }
}

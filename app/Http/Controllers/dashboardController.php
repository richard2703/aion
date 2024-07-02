<?php

namespace App\Http\Controllers;

use App\Models\Personalizar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class dashboardController extends Controller
{
    //
    public function getConfig()
    {
        $personalizar = Personalizar::first();


        return response()->json($personalizar);
    }


    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'proposito' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => $validator->errors()->first()], 422);
        }

        // Prepare data to save
        $data = [
            'proposito' => $request->proposito,
        ];

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $path1 = $logo->store('uploads', 'public');
            $data['logo'] = $path1;
        }

        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $path2 = $banner->store('uploads', 'public');
            $data['banner'] = $path2;
        }

        // Save data to database
        $personalizar = new Personalizar();
        $personalizar->fill($data);
        $personalizar->save();

        // Return response
        return response()->json(['message' => 'Updated successfully', 'personalizar' => $personalizar->fresh()]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'proposito' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $personalizar = Personalizar::findOrFail($id);

        $personalizar->proposito = $request->proposito;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $path1 = $logo->store('uploads', 'public');
            $personalizar->logo = $path1;
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $path2 = $banner->store('uploads', 'public');
            $personalizar->banner = $path2;
        }

        $personalizar->save();

        return response()->json(['message' => 'Updated successfully', 'personalizar' => $personalizar->fresh()]);
    }
}

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

        if (!$personalizar) {
            return response()->json([
                'message' => 'No configuration found.',
            ], 404);
        }

        $data = [
            'id' => $personalizar->id,
            'proposito' => $personalizar->proposito,
            'logo' => null, // Placeholder for logo URL
            'banner' => null, // Placeholder for banner URL
        ];

        if ($personalizar->logo) {
            $data['logo'] = Storage::disk('logos')->url($personalizar->logo); // Construct logo URL from storage path
        }

        if ($personalizar->banner) {
            $data['banner'] = Storage::disk('banners')->url($personalizar->banner); // Construct banner URL from storage path
        }

        return response()->json($data);
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


    function update(Request $request, $id)
    {
        $proposito = $request->proposito;

        $profile = Personalizar::find($id); // Find profile by ID

        $data = [
            'proposito' => $proposito,
        ];
        dd($data, $id);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos'); // Store logo in 'logos' disk
            $data['logo'] = $logoPath;

            // Optionally delete the old logo if applicable (implement logic here)
        }

        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners'); // Store banner in 'banners' disk
            $data['banner'] = $bannerPath;

            // Optionally delete the old banner if applicable (implement logic here)
        }

        $profile->update($data);

        return response()->json(['message' => 'Updated successfully', 'profile' => $profile->fresh()]); // Return updated profile data

    }
}

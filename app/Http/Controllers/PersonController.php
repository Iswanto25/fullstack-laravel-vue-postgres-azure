<?php

namespace App\Http\Controllers;

use Hidehalo\Nanoid\Client;
use Hidehalo\Nanoid\GeneratorInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Models\Person;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //code...
            $person = Person::all();
            return response()->json([
                'status' => 'success',
                'message' => 'Data person berhasil diambil',
                'data' => $person,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => 'Data person gagal diambil'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|file|max:2048',
                'name' => 'nullable',
                'address' => 'nullable',
                'phone' => 'nullable|numeric',
                'email' => 'nullable|email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ]);
            }
            $client = new Client();
            $id = $client->formattedId($alphabet = '0123456789', $size = 5);

            $image = $request->file('image');
            $image->storeAs('public/images', $id . '-' . $image->hashName());

            $person = Person::create([
                'id' => $id,
                'image' => $id . '-' . $image->hashName(),
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data person berhasil ditambahkan',
                'data' => $person,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => 'Data person gagal ditambahkan'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            //code...
            $person = Person::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data person berhasil diambil',
                'data' => $person,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => 'Data person tidak ditemukan'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            //code...
            $person = Person::findOrFail($id);

            if (!$person) {
                # code...
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data person tidak ditemukan',
                ]);
            }

            $validator = Validator::make($request->all(), [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|file|max:2048',
                'name' => 'nullable',
                'address' => 'nullable',
                'phone' => 'nullable|numeric',
                'email' => 'nullable|email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ]);
            }

            $image = $request->file('image');
            $imageFileName = $person->image;

            if ($image) {
                Storage::delete('public/images/' . $imageFileName);
                $image->storeAs('public/images', $id . '-' . $image->hashName());
                $imageFileName = $id . '-' . $image->hashName();
            }

            $person->update([
                'image' => $imageFileName,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data person berhasil diupdate',
                'data' => $person,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => 'Data person gagal diupdate'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $person = Person::findOrFail($id);

            $imageFileName = $person->image;

            if ($imageFileName) {
                Storage::delete('public/images/' . $imageFileName);
            }

            $person->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data person dan file gambar terkait berhasil dihapus',
                'data' => $person,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data person tidak ditemukan',
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Person;
use Illuminate\Support\Str;

use App\Http\Resources\PersonResource;

class PersonController extends Controller
{
    public function index()
    {
        $resource = Person::all();
        return new PersonResource(
            true,
            'Success',
            $resource,
        );
    }

    public function store(Request $resource)
    {
        $validator = Validator::make($resource->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|numeric|unique:person,phone',
            'email' => 'required|email|unique:person,email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $resource->file('image');
        $image->storeAs('public/image', $image->hashName());

        $post = Person::create([
            'id' => Str::uuid(),
            'image' => $image->hashName(),
            'name' => $resource->name,
            'address' => $resource->address,
            'phone' => $resource->phone,
            'email' => $resource->email,
        ]);

        return new PersonResource(
            true,
            'Success',
            $post,
        );
    }

    public function update(Request $resource, $id)
    {
        $validator = Validator::make($resource->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $resource->file('image');
        $image->storeAs('public/image', $image->hashName());

        $post = Person::where('id', $id)->update([
            'image' => $image->hashName(),
            'name' => $resource->name,
            'address' => $resource->address,
            'phone' => $resource->phone,
            'email' => $resource->email,
        ]);

        return new PersonResource(
            true,
            'Success',
            $post,
        );
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $products = Brand::with('category')->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'category_id' => 'required',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $productData = $request->except('image');
        $productData['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/brands'), $imageName);
            $productData['image'] = 'uploads/brands/' . $imageName;
        }

        $product = Brand::create($productData);

        return response()->json([
            'product' => $product,
            'message' => 'Brand created successfully'
        ], 201);
    }

    public function show($id)
    {
        $product = Brand::with('category')->findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $productData = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/brands'), $imageName);
            $productData['image'] = 'uploads/brands/' . $imageName;
        }

        $product->update($productData);

        return response()->json([
            'product' => $product,
            'message' => 'Brand updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $product = Brand::findOrFail($id);

        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return response()->json(['message' => 'Brand deleted successfully']);
    }
}

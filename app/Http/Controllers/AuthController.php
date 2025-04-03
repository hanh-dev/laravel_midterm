<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showSignupForm() {
        return view('pages.auth.signup');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect('/signin')->with('success', 'Đăng ký thành công, hãy đăng nhập!');
    }

    public function showSigninForm() {
        return view('pages.auth.signin');
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user', $user);
            return redirect('/home')->with('success', 'Login successful!');
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }
// fjdk










    public function products(){
        $products = Product::all();
        return response()->json($products);
    }

    public function detail($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_price' => 'required|numeric',
            'promotion_price' => 'nullable|numeric',
            'unit' => 'required|string|max:50',
            'new' => 'required|boolean',
            'id_type' => 'required|integer|exists:type_products,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('products', 'public');
        }
    
        // Lưu vào database
        $product = Product::create([
            'name' => $request->name,
            'unit_price' => $request->unit_price,
            'promotion_price' => $request->promotion_price,
            'unit' => $request->unit,
            'new' => $request->new,
            'id_type' => $request->id_type,
            'image' => $imagePath, 
            'description' => $request->description,
        ]);
    
        return response()->json([
            'message' => 'Sản phẩm đã được tạo thành công!',
            'product' => $product
        ], 201);
    }    

    public function updateProduct(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'unit_price' => 'sometimes|required|numeric',
            'promotion_price' => 'sometimes|nullable|numeric',
            'unit' => 'sometimes|required|string|max:50',
            'new' => 'sometimes|required|boolean',
            'id_type' => 'sometimes|required|integer|exists:type_products,id',
            'image' => 'sometimes|required|string',
            'description' => 'sometimes|nullable|string',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}


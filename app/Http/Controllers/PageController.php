<?php

namespace App\Http\Controllers;

use App\Models\BillDetail;
use App\Models\Comment;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        $slide = Slide::all();
        $new_product = Product::where('new', 1)->paginate(4);
        $promotion_product = Product::where('promotion_price', '<>', 0)->paginate(8);
        return view('pages.trangchu', compact('slide', 'new_product', 'promotion_product'));
    }

    public function addCart() {
        
    }

    public function getLoaiSp($type) {
        $sp_theoloai = Product::where('id_type', $type)->get();
        $type_product = ProductType::all();
        $sp_khac = Product::where('id_type', '<>', $type)->paginate(3);
        return view('pages.product_type', compact('sp_theoloai', 'type_product', 'sp_khac'));
    }

    public function getDetail(Request $request) {
        $sanpham = Product::where('id', $request->id)->first();
        $comments = Comment::where('id_product', $request->id)->get();
        $splienquan = Product::where('id_type', $sanpham->id_type)
        ->where('id', '!=', $sanpham->id)
        ->paginate(3);

        return view('pages.chitiet_sanpham', compact('sanpham', 'splienquan', 'comments'));
    }

    public function getAboutUs() {
        return view('pages.about');
    }

    public function getContact() {
        return view('pages.lienhe');
    }

    // Admin
    public function getIndexAdmin() {
        $products = Product::all();
        return view('pages.admin.admin')->with(['products'=>$products, 'sumSold'=>count(BillDetail::all())]);
    }

    public function getAdminAdd() {
        return view('pages.admin.formAdd');
    }

        							
    public function postAdminAdd(Request $request) 
    {
        // $request->validate([
        //     'inputName' => 'required|string|max:255',
        //     'inputImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        //     'inputDescription' => 'nullable|string',
        //     'inputPrice' => 'required|numeric|min:0',
        //     'inputPromotionPrice' => 'nullable|numeric|min:0',
        //     'inputUnit' => 'required|string|max:50',
        //     'inputNew' => 'required|boolean',
        //     'inputType' => 'required|integer|exists:type_products,id',
        // ]);
    
        $product = new Product();
        
        if ($request->hasFile('inputImage')) {
            $file = $request->file('inputImage');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('image/product'), $fileName);
            $product->image = $fileName;
        }
    
        $product->name = $request->input('inputName');
        $product->description = $request->input('inputDescription');
        $product->unit_price = $request->input('inputPrice');
        $product->promotion_price = $request->input('inputPromotionPrice') ?? 0;
        $product->unit = $request->input('inputUnit');
        $product->new = $request->input('inputNew');
        $product->id_type = $request->input('inputType');
    
        $product->save();
    
        return $this->getIndexAdmin();
    }

	public function getAdminEdit($id)												
	{												
        $product = Product::find($id);												
        return view('pages.admin.formEdit')->with('product', $product);												
    }
    
    public function postAdminEdit(Request $request) {
        $id = $request->id;
        $product = Product::find($id);
        if($request->hasFile('editImage')) {
            $file = $request->file('editImage');
            $fileName = $file->getClientOriginalName('editImage');
            $file->move(public_path('image\product'), $fileName);

            // $file->move('image/product', $fileName);
        }

        if($request->file('editImage') != null) {
            $product->image = $fileName;
        }

        $product->name = $request->editName;
        $product->description = $request->editDescription;
        $product->unit_price = $request->editPrice;
        $product->promotion_price = $request->editPromotionPrice;
        $product->unit = $request->editUnit;
        $product->new = $request->editNew;
        $product->id_type = $request->editType;
        $product->save();
        return $this->getIndexAdmin();
    }

    public function postAdminDelete($id) {
        $product = Product::find($id);
        $product->delete();
        return $this->getIndexAdmin();
    }

    public function comment(Request $request, $id) {
        $userID = session('user_id');
        if (!$userID) {
            return back()->withErrors(['message' => 'You must be logged in to comment.']);
        }
    
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);
    
        $user = User::find($userID);
        if (!$user) {
            return back()->withErrors(['message' => 'User not found.']);
        }
    
        $product = Product::find($id);
        if (!$product) {
            return back()->withErrors(['message' => 'Product not found.']);
        }
    
        $comment = new Comment();
        $comment->id_product = $id;
        $comment->username = $user->name;
        $comment->comment = $request->input('comment');
        $comment->save();
    
        return back()->with('success', 'Comment added successfully!');
    }
}
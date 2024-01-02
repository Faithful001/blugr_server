<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogModel;
use App\Models\UserModel;


class BlogController extends Controller
{
    public function getBlogs(){
        try {
            $blogs = BlogModel::all();
            if($blogs->count() > 0){
                return response()->json([
                    "status"=>true,
                    "message"=>'all blogs',
                    'data'=>$blogs
                ], 200);
            }else{
                return response()->json([
                    "status"=>false,
                    "error"=>"No blog created",
                    "data" => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status"=>false,
                "error"=>$e->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function getBlog($id){
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new \Exception("Invalid blog ID", 400);
            }
            $blog = BlogModel::find($id);
            if($blog){
                return response()->json([
                    "status"=> true,
                    'message'=>'blog',
                    "data"=>$blog
                ], 200);
            } else{
                return response()->json([
                    "status"=> false,
                    "error"=>"Blog not found",
                    "data" => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status"=>false,
                "error"=>$e->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function createBlog(Request $req){
        try {
            $user = Auth::user();

            if(!user){
                throw new \Exception('User not authenticated', 401);
            }

            $req->validate([
                'title'=>'required|string',
                'body'=>'required|string',
                'user_id'=>'required|string'
            ]);

            $blogExists = BlogModel::where('title', $req->title)->exists();

            if($blogExists){
                throw new \Exception("Blog already exists", 404);
            }

            $blog = new BlogModel([
                'title'=>$req->title,
                'body'=>$req->body,
                'user_id'=>$user->id
            ]);

            $blog->save();

            return response()->json([
                'status'=>true,
                'message'=>'Blog created successfully',
                'data'=>$blog
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                "status"=>false,
                "error"=>$e->getMessage(),
                "data" => null
            ], 500);
        }
    }

    public function updateBlog(Request $req, $id){
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new \Exception("Invalid blog ID", 400);
            }
            $blog = BlogModel::find($id);
            if(!$blog){
                throw new \Exception('Blog does not exist', 404);
            }

            $req->validate([
                'title' => 'required|string',
                'body' => 'required|string',
            ]);

            $blog->title = $req->title;
            $blog->body = $req->body;
            $blog->save();

            return response()->json([
                'status'=>true,
                'message'=>'Blog updated successfully',
                'data'=>$blog
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'=>false,
                "error"=>$e->getMessage(),
                "data" => null
            ]);
        }
    }

    public function deleteBlog($id){
        try {
            if (!is_numeric($id) || $id <= 0) {
                throw new \Exception("Invalid blog ID", 400);
            }
            $blog = BlogModel::find($id);
            if(!$blog){
                throw new \Exception('Blog does not exist', 404);
            }

            $blog->delete();

            return response()->json([
                'status'=>true,
                'message'=>'Blog deleted successfully',
                'data'=>null
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'=>false,
                "error"=>$e->getMessage(),
                "data" => null
            ]);
        }
    }
}

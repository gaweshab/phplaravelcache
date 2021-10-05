<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Redis;

class BlogController extends Controller
{
    /**
     * This will get the blog data from the database.
     * First it will check blog data exists in the cache and
     * then it will set the cache if it is not set.
     * 
     * @param int $id
     * @return json responce
     */
    public function getBlog($id) {

        $cachedBlog = Redis::get('blog_' . $id);
      
        if(isset($cachedBlog)) {
            $blog = json_decode($cachedBlog, FALSE);
      
            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from redis',
                'data' => $blog,
            ]);
        }else {
            $blog = Blog::getBlog($id);
            Redis::set('blog_' . $id, $blog);
      
            return response()->json([
                'status_code' => 201,
                'message' => 'Fetched from database',
                'data' => $blog,
            ]);
        }
    }

    /**
     * Here it will update the blog record using the blog record
     * id and the post request. This will update the cache.
     * 
     * @param  Illuminate\Http\Request $request
     * @param int $id 
     * @return json responce
     */
    public function updateBlog(Request $request, $id) {

        $update = Blog::findOrFail($id)->update($request->all());
      
        if($update) {
      
            // Delete blog_$id from Redis
            Redis::del('blog_' . $id);
      
            $blog = Blog::getBlog($id);
            // Set a new key with the blog id
            Redis::set('blog_' . $id, $blog);
      
            return response()->json([
                'status_code' => 201,
                'message' => 'User updated',
                'data' => $blog,
            ]);
        }
      
    }

    /**
     * Here it will delete the blog record using the blog record
     * id. This will update the cache as well.
     * 
     * @param int $id 
     * @return json responce
     */
    public function deleteBlog($id) {

        Blog::findOrFail($id)->delete();
        Redis::del('blog_' . $id);
      
        return response()->json([
            'status_code' => 201,
            'message' => 'Blog deleted'
        ]);
    }
}

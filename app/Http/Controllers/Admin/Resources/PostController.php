<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Services\AdminModelService;
use App\Services\IndexService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class PostController extends Controller
{

    /**
     * Rules that should be used in creating.
     * @return array<string, string|array>
     */
    protected function createRules()
    {
        return [
            'user_id' => ['required', 'numeric'],
            'caption' => ['nullable', 'string'],
            'post_image' => ['required', 'image'],
            'alternate_text' => ['nullable', 'string', 'max:255']
        ];
    }

    /**
     * Rules that should be used in updating.
     * @param Post  $post
     * @return array<string, string|array>
     */
    protected function updateRules(Post $post)
    {
        return [
            'user_id' => ['sometimes', 'required', 'numeric'],
            'caption' => ['sometimes', 'nullable', 'string'],
            'post_image' => ['sometimes', 'image'],
            'alternate_text' => ['sometimes', 'nullable', 'string', 'max:255']
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = ['created_at', 'updated_at'];
        $service = new IndexService;
        $service->processRequest($request, Post::class, $filters);

        $adminModelService = new AdminModelService;
        $adminModelService->shareAdminModel(Post::class, ['user_id' => ['model' => User::class, 'fieldToShow' => 'username']]);

        return view('admin.model.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $adminModelService = new AdminModelService;
        $adminModelService->shareAdminModel(Post::class, ['user_id' => ['model' => User::class, 'fieldToShow' => 'username']]);

        return view('admin.model.create', ['createRules' => $this->createRules()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->createRules());

        $user = User::find($request->user_id);
        $post = $user->posts()->create(['caption' => $request->caption]);

        $imagePath = Cloudinary::upload($request->file('post_image')->getRealPath(), [
            'width' => 1080,
            'height' => 1080,
            'crop' => 'fit',
        ])->getSecurePath();

        $post->images()->create(['image' => $imagePath, 'alternate_text' => $request->alterate_text]);

        $adminModelService = new AdminModelService;
        $adminModelService->shareAdminModel(Post::class, ['user_id' => ['model' => User::class, 'fieldToShow' => 'username']]);

        return $this->responseBasedOnNext($request, $post)->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Post $post)
    {
        $adminModelService = new AdminModelService;
        $adminModelService->shareAdminModel(Post::class, ['user_id' => ['model' => User::class, 'fieldToShow' => 'username']]);

        $image = $post->images()->first();
        $post->setAttribute('post_image', $image->image);
        $post->setAttribute('alternate_text', $image->alternate_text);

        return view('admin.model.edit', ['updateRules' => $this->updateRules($post), 'model' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate($this->updateRules($post));

        $post->update(['user_id' => $request->user_id, 'caption' => $request->caption]);

        $image = $post->images()->first();

        if ($request->hasFile('post_image')) {
            $imagePath = Cloudinary::upload($request->file('post_image')->getRealPath(), [
                'width' => 1080,
                'height' => 1080,
                'crop' => 'fit',
            ])->getSecurePath();
            $image->image = $imagePath;
        }

        $image->alternate_text = $request->alternate_text;

        $image->save();

        return $this->responseBasedOnNext($request, $post)->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param Post  $post
     * @return \Illuminate\Http\Response
     */
    private function responseBasedOnNext(Request $request, Post $post)
    {
        if ($request->has('next')) {
            if ($request->next == 'save-and-create') {
                $response =  redirect()->route('admin.posts.create');
            } elseif ($request->next == 'save-and-continue') {
                $response = redirect()->route('admin.posts.edit', ['post' => $post]);
            } else {
                $response = redirect()->route('admin.posts.index');
            }
        } else {
            $response = back();
        }
        return $response;
    }
}

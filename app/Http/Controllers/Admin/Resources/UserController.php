<?php

namespace App\Http\Controllers\Admin\Resources;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminModelService;
use App\Services\IndexService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Rules that should be used in creating.
     * @return array<string, string|array>
     */
    protected function createRules()
    {
        return [
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date', 'before_or_equal:' . today()->subYears(5)],
            'password' => ['required', 'string', 'min:8'],
            'profile_image' => ['nullable', 'image'],
            'website' => ['nullable', 'url'],
            'bio' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255', 'unique:users'],
            'gender' => ['in:prefer not to say,male,female'],
            // 'email_verified_at' => ['nullable', 'date'],
            // 'remember_token' => ['nullable', 'string', 'max:100'],
            // 'facebook_id' => ['nullable', 'string', 'max:255'],
            // 'facebook_token' => ['nullable', 'string', 'max:2048'],
            // 'facebook_refresh_token' => ['nullable', 'string', 'max:2048'],
            'is_admin' => ['boolean']
        ];
    }

    /**
     * Rules that should be used in updating.
     * @param  User $user
     * @return array<string, string|array>
     */
    protected function updateRules(User $user)
    {
        return [
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'username' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('users')->ignore($user)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'birthday' => ['sometimes', 'required', 'date', 'before_or_equal:' . today()->subYears(5)],
            'profile_image' => ['sometimes', 'nullable', 'image'],
            'website' => ['sometimes', 'nullable', 'url'],
            'bio' => ['sometimes', 'nullable', 'string'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('users')->ignore($user)],
            'gender' => ['sometimes', 'in:prefer not to say,male,female'],
            'email_verified_at' => ['sometimes', 'nullable', 'date'],
            // 'remember_token' => ['sometimes', 'nullable', 'string', 'max:100'],
            // 'facebook_id' => ['sometimes', 'nullable', 'string', 'max:255'],
            // 'facebook_token' => ['sometimes', 'nullable', 'string', 'max:2048'],
            // 'facebook_refresh_token' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'is_admin' => ['sometimes', 'boolean']
        ];
    }

    /**
     * Rules that should be used in updating.
     * @return array<string, string|array>
     */
    protected function updatePasswordRules()
    {
        return [
            'new_password' => ['required', 'string', 'min:8'],
            'confirmed_new_password' => ['required', 'string', 'min:8', 'same:new_password'],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = ['is_admin', 'created_at', 'updated_at'];
        $indexService = new IndexService;
        $indexService->processRequest($request, User::class, $filters);

        $adminModelService = new AdminModelService;
        $adminModelService->shareAdminModel(User::class);

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
        $adminModelService->shareAdminModel(User::class);

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
        $request->merge(['is_admin' => $request->boolean('is_admin')]);

        $request->validate($this->createRules());

        $request->merge(['password' => Hash::make($request->password)]);

        $user = User::create($request->except('profile_image'));

        if ($request->hasFile('profile_image')) {
            $user->addProfileImage($request->file('profile_image'));
        }

        $adminModelService = new AdminModelService;
        $adminModelService->shareAdminModel(User::class);

        return $this->responseBasedOnNext($request, $user)->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $adminModelService = new AdminModelService;
        $adminModelService->shareAdminModel(User::class);

        return view('admin.user.edit', ['model' => $user, 'updateRules' => $this->updateRules($user)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function editPassword(User $user)
    {
        $adminModelService = new AdminModelService;
        $adminModelService->shareAdminModel(User::class);

        return view('admin.user.edit-password', ['model' => $user, 'updatePasswordRules' => $this->updatePasswordRules()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->merge(['is_admin' => $request->boolean('is_admin')]);

        $request->validate($this->updateRules($user));

        $user->update($request->except('profile_image'));

        if ($request->hasFile('profile_image')) {
            $user->addProfileImage($request->file('profile_image'));
        }

        $adminModelService = new AdminModelService;
        $adminModelService->shareAdminModel(User::class);

        return $this->responseBasedOnNext($request, $user)->with('success', 'User updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate($this->updatePasswordRules());

        $user->update(['password' => Hash::make($request->new_password)]);


        return $this->responseBasedOnNext($request, $user)->with('success', "User's password updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param User  $user
     * @return \Illuminate\Http\Response
     */
    private function responseBasedOnNext(Request $request, User $user)
    {
        if ($request->has('next')) {
            if ($request->next == 'save-and-create') {
                $response =  redirect()->route('admin.users.create');
            } elseif ($request->next == 'save-and-continue') {
                $response = redirect()->route('admin.users.edit', ['user' => $user]);
            } else {
                $response = redirect()->route('admin.users.index');
            }
        } else {
            $response = back();
        }
        return $response;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\Member;
use App\Models\GroupOwner;


class GroupController extends Model 
{
    public function show(int $id) {
        $group = Group::find($id);
        $posts = $group->posts()->get();
        return view('pages.group', ['group' => $group]);
    }

    public function showCreateForm() {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'You cannot create a group');
        }

        return view('pages.createGroup');
    }

    public function showEditForm($id) {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'You cannot edit a group');
        }

        $group = Group::find($id);

        if (!$group->is_owner(Auth::user())) {
            return redirect()->route('home')->with('error', 'You cannot edit a group you do not own');
        }

        return view('pages.editGroup', ['group' => $group]);
    }

    public function list()
    {
        $groups = DB::table('groups')->simplePaginate(20);
        return view('pages.groups', ['groups' => $groups]); 
    }


    public function create(Request $request) {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'You cannot create a group');
        }

        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'visibility' => 'required|boolean',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $group = new Group();
            $group->name = $request->name;
            $group->banner = ($request->file('banner') != null) ? 'data:image/png;base64,' . base64_encode(file_get_contents($request->file('banner'))) : null;
            $group->description = $request->description;
            $group->public_group = $request->visibility;
            $group->date = date('Y-m-d H:i:s');

            $group->save();

            // Add user as member
            $groupMember = new Member();
            $groupMember->user_id = Auth::user()->id;
            $groupMember->group_id = $group->id;
            $groupMember->date = date('Y-m-d H:i:s');
            $groupMember->save();
            

            // Add user as owner 
            $groupOwner = new GroupOwner();
            $groupOwner->user_id = Auth::user()->id;
            $groupOwner->group_id = $group->id;
            $groupOwner->date = date('Y-m-d H:i:s');
            $groupOwner->save();

            DB::commit();

            return redirect()->route('group', ['id' => $group->id])->with('success', 'Group created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('groups')->with('error', 'Group creation failed');
        }

    }

    public function edit(Request $request) {
        $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'required|string|max:255',
            'visibility' => 'required|boolean',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $group = Group::find($request->id);
            $group->name = $request->name;
            $group->banner = ($request->file('banner') != null) ? 'data:image/png;base64,' . base64_encode(file_get_contents($request->file('banner'))) : null;
            $group->description = $request->description;
            $group->public_group = $request->visibility;
            $group->date = date('Y-m-d H:i:s');

            $group->save();

            DB::commit();

            return redirect()->route('group', ['id' => $group->id])->with('success', 'Group edited successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('groups')->with('error', 'Group edit failed');
        }
    }

}
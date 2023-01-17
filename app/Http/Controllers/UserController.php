<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    // private $users;

    const _PER_PAGE = 2;

    public static function users() {
        return new User();
    }

    // public function __construct() {
    //     $this->users = self::users();
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        // $this->users->eloquent();
        $filters = [];
        $keyword = null;
       
        if (!empty($request->status)) {
            $status = $request->status;
            if ($status=='active') {
                $status = 1;
            } else {
                $status = 0;
            }
            $filters[] = ['users.status', '=', $status];
        }

        if (!empty($request->group_id)) {
            $groupId = $request->group_id;

            $filters[] = ['users.group_id', '=', $groupId];
        }

        if (!empty($request->keyword)) {
            $keyword = $request->keyword;
        }

        $sortBy = $request->input('sort-by');
        $sortType = $request->input('sort-type');
        $allowSort = ['asc', 'desc'];
        // ?$request->input('sort-type'):'asc';
        if(!empty($sortType) && in_array($sortType, $allowSort)){
            if ($sortType=='desc'){
                $sortType = 'asc';
            } else {
                $sortType = 'desc';
            }
        } else {
            $sortType = 'asc';
        }
        $sortArr = [
            'sortBy' => $sortBy,
            'sortType' => $sortType
        ];
        $usersList = $this->users()->getAllUsers($filters, $keyword, $sortArr, self::_PER_PAGE);

        return view('clients.users.lists', compact('usersList',  'sortType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allGroups = getAllGroups();

        return view('clients.users.add', compact('allGroups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $dataInsert =$request->merge(['created_at' => date('Y-m-d H:i:s')])
                    ->only(['name', 'email', 'group_id', 'status', 'created_at']);
        $this->users()->addUser($dataInsert);

        return redirect()->route('user.index')->with('msg', 'Add user success!');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $user=0)
    {
        if (empty($user)) {
            return redirect()->route('user.index')->with('msg', 'User does not exist!');
        } 
        $request->session()->put('id', $user->id);
        $allGroups = getAllGroups();

        return view('clients.users.edit', compact('allGroups', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request)
    {
        $id = session('id');
        if (empty($id)) {
            return back()->with('msg', 'User does not exist!');
        }
        $dataUpdate = $request->merge(['updated_at' => date('Y-m-d H:i:s')])
                    ->only(['name', 'email', 'group_id', 'status', 'updated_at']);
        $this::users()->updateUser($dataUpdate, $id);

        return back()->with('msg', 'Update success!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        if (!empty($user)) {
            $deleteStatus = $this->users()->deleteUser($user->id);
            if ($deleteStatus) {
                $msg = 'Delete success!';
            } else {
                $msg = 'Delete fail!';
            }
        } else {
            $msg = 'User does not exist!';
        }
        
        return redirect()->route('user.index')->with('msg', $msg);
    }
}

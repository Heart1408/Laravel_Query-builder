<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    private $users;

    const _PER_PAGE = 2;

    public function __construct() {
        $this->users = new Users();
    }

    public function index(Request $request){
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
        $usersList = $this->users->getAllUsers($filters, $keyword, $sortArr, self::_PER_PAGE);

        return view('clients.users.lists', compact('usersList',  'sortType'));
    }

    public function add(){
        $allGroups = getAllGroups();

        return view('clients.users.add', compact('allGroups'));
    }

    public function postAdd(UserRequest $request){
        $dataInsert = [
            'name' => $request->name,
            'email' => $request->email,
            'group_id' => $request->group_id,
            'status' => $request->status,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->users->addUser($dataInsert);

        return redirect()->route('users.index')->with('msg', 'Add user success!');
    }

    public function getEdit(Request $request, $id=0) {
        if (!empty($id)) {
            $userDetail = $this->users->getDetail($id);
            // dd($userDetail);
            if (!empty($userDetail[0])) {
                $request->session()->put('id', $id);
                $userDetail = $userDetail[0];
            } else {
                return redirect()->route('users.index')->with('msg', 'User does not exist!');
            }
        } else {
            return redirect()->route('users.index')->with('msg', 'User does not exist!');
        }

        $allGroups = getAllGroups();

        return view('clients.users.edit', compact('userDetail', 'allGroups'));
    }

    public function postEdit(UserRequest $request) {
        // dd(session('id'));
        $id = session('id');
        if (empty($id)) {
            return back()->with('msg', 'User does not exist!');
        }

        $dataUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'group_id' => $request->group_id,
            'status' => $request->status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->users->updateUser($dataUpdate, $id);

        return back()->with('msg', 'Update success!');
    }

    public function delete($id=0) {
        if (!empty($id)) {
            $userDetail = $this->users->getDetail($id);
            // dd($userDetail);
            if (!empty($userDetail[0])) {
                $deleteStatus = $this->users->deleteUser($id);
                if ($deleteStatus) {
                    $msg = 'Delete success!';
                } else {
                    $msg = 'Delete fail!';
                }
            } else {
                $msg = 'User does not exist!';
            }
        } else {
            $msg = 'User does not exist!';
        }
        return redirect()->route('users.index')->with('msg', $msg);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class Users extends Model
{
    use HasFactory;

    protected $table = 'users';

    public function getAllUsers($filters = [], $keyword = null, $sortByArr = null, $perPage = null)
    {
        // DB::enableQueryLog();
        $users = DB::table($this->table)
        ->select('users.*', 'groups.name as group_name')
        ->join('groups', 'users.group_id', '=', 'groups.id');

        $orderBy = 'users.created_at';
        $orderType = 'DESC';
        if (!empty($sortByArr) && is_array($sortByArr)) {
            if (!empty($sortByArr['sortBy']) && !empty($sortByArr['sortType'])) {
                $orderBy = trim($sortByArr['sortBy']);
                $orderType = trim($sortByArr['sortType']);
            }
        };

        $users = $users->orderBy($orderBy, $orderType);

        if (!empty($filters)) {
            $users = $users->where($filters);
        }

        if (!empty($keyword)) {
            $users = $users->where(function($query) use ($keyword) {
                $query->orWhere('users.name', 'like', '%'.$keyword.'%');
                $query->orWhere('users.email', 'like', '%'.$keyword.'%');
            });
        }

        if (!empty($perPage)) {
            $users = $users->paginate($perPage)->withQueryString();
        } else {
            $users = $users->get();
        }
        // $sql = DB::getQueryLog();
        // dd($sql);

        return $users;
    }

    public function addUser($data)
    {
        DB::table($this->table)->insert($data);
    }

    public function learnQueryBuilder()
    {
        return DB::statement($sql);
    }

    public function getDetail($id)
    {
        return DB::select('SELECT * FROM '.$this->table.' WHERE id = ?', [$id]);
    }

    public function updateUser($data, $id)
    {
        return DB::table($this->table)->where('id', $id)->update($data);
    }

    public function deleteUser($id)
    {
        return DB::table($this->table)->where('id', $id)->delete();
    }

    public function queryBuilder()
    {
        $list = DB::table('users')
        ->where(
            'group_id',
            '=',
            function($query) {
                $query->select('id')->from('groups')->where('name', '=', 'HHH');
            }
        ) 
        // ->select('email', DB::raw('(SELECT count(id) FROM groups) as group_count'))
        ->selectRaw('email, (SELECT count(id) FROM groups) as group_count')
        ->get();

        // $l = DB::raw("SELECT id FROM groups WHERE name='a'");
        // dd($l);

        dd($list);
    }
}

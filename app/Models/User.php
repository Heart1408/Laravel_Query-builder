<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function groups()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function getGroup($user_id) 
    {
        $group =  $this->find($user_id)->groups()->first();
        if (is_null($group)) return '-';
        return $group->name;
    }

    public function getAllUsers($filters = [], $keyword = null, $sortByArr = null, $perPage = null)
    {
        $orderBy = 'users.created_at';
        $orderType = 'DESC';
        if (!empty($sortByArr) && is_array($sortByArr)) {
            if (!empty($sortByArr['sortBy']) && !empty($sortByArr['sortType'])) {
                $orderBy = trim($sortByArr['sortBy']);
                $orderType = trim($sortByArr['sortType']);
            }
        };

        $users = $this->orderBy($orderBy, $orderType);

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
        $this->insert($data);
    }

    public function getDetail($id)
    {
        return $this->where('id', $id)->get();
    }

    public function updateUser($data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    public function deleteUser($id)
    {
        return $this->where('id', $id)->delete();
    }

    public function eloquent()
    {
        // $list = $this
        // ->where(
        //     'group_id',
        //     '=',
        //     function($query) {
        //         $query->select('id')->from('groups')->where('name', '=', 'HHH');
        //     }
        // ) 
        // ->selectRaw('email, (SELECT count(id) FROM groups) as group_count')
        // ->get();

        // $groupHHH=Group::where("name","HHH")->first();
        // $usersOfGroupHHH=$groupHHH->users();
        // $emailsOfUsers=$usersOfGroupHHH->pluck("email");
        // $countOfUsers=$usersOfGroupHHH->count(); 

        $emailsOfUsers=Group::where("name","HHH")->first()->users()->pluck("email");

        dd($emailsOfUsers);
    }
}

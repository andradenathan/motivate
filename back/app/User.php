<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Http\Requests\UserRequest;
use Laravel\Passport\HasApiTokens;
    
class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function createUser(UserRequest $request) {
        $this->name = $request->name;
        $this->email = $request->email;
        $this->password = bcrypt($request->password);
        $this->photo = $request->photo;
        $this->gender = $request->gender;
        $this->date_of_birth = $request->date_of_birth;
        $this->save();

        return $this;
    }

    public function updateUser(userRequest $request) {
        if ($request->name) {
            $this->name = $request->name;
        }
        if ($request->email) {
            $this->email = $request->email;
        }
        if ($request->password) {
            $this->password = $request->password;
        }
        if ($request->photo) {
            $this->photo = $request->photo;
        }
        if ($request->gender) {
            $this->gender = $request->gender;
        }
        if ($request->date_of_birth) {
            $this->date_of_birth = $request->date_of_birth;
        }
        $this->save();
    }

    public function recipes() {
        return $this->hasMany('App/Recipe');
    }

    // Relação da postagem de comentários com as comments
    public function commentsMadeByUser(){
        return $this->hasMany('App\Comment');    
    }
    // Relação de seguir usuários
    public function follower(){
        return $this->belongsToMany('App\User', 'follows', 'follower_id',  'following_id');    
    }

    public function followUser($user_id) {
        $user = User::findOrFail($user_id);
        $this->follower()->attach($user);
    }

    // Relação de usuários sendo seguidos
    public function following(){
        return $this->belongsToMany('App\User', 'follows', 'following_id', 'follower_id');
    }
}

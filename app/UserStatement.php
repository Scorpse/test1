<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStatement extends Model{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['id', 'user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
	protected $hidden   = ['currency','updated_at','created_at','balance','bonus_balance', 'deposits','withdrawals','bonus_status'];

    /**
     * Define a one-to-many relationship with App\Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function transactions(){
		return $this->hasMany('App\Transaction');
	}

}
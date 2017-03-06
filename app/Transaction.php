<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['id', 'user_statement_id', 'user_id', 'amount', 'transaction_type', 'bonus'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
	protected $hidden   = ['created_at', 'updated_at','transaction_hash'];

    /**
     * Define an inverse one-to-many relationship with App\Post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function userStatement(){
		return $this->belongsTo('App\UserStatement');
	}

}
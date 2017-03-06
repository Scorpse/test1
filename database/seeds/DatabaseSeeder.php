<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\UserStatement;
use App\Transaction;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(){

		// Disable foreign key checking because truncate() will fail
		DB::statement('SET FOREIGN_KEY_CHECKS = 0');

		//User::truncate();
		//UserStatement::truncate();
		//Transaction::truncate();

	//	factory(User::class, 10)->create();
	//	factory(UserStatement::class, 10)->create();
		factory(Transaction::class, 200)->create();

		//$this->call('OAuthClientSeeder');

		// Enable it back
		DB::statement('SET FOREIGN_KEY_CHECKS = 1');
	}

}

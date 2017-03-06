<?php

namespace App\Http\Controllers;

use App\Transaction;

class TransactionController extends Controller{

	public function index(){
		
		$transactions = Transaction::all();
		return $this->success($transactions, 200);
	}

	public function show($id){

		$transaction = Transaction::find($id);

		if(!$transaction){
			return $this->error("The transaction with {$id} doesn't exist", 404);
		}

		return $this->success($transaction, 200);
	}
}
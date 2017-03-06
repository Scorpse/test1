<?php 

namespace App\Http\Controllers;

use App\User;
use App\UserStatement;
use App\Transaction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserStatementTransactionController extends Controller{

	public function __construct(){
		
		$this->middleware('oauth', ['except' => ['index', 'show']]);
        $this->middleware('authorize:' . __CLASS__, ['except' => ['index', 'show', 'store']]);
	}

	public function index($userStatement_id){

		$userStatement = UserStatement::find($userStatement_id);

		if(!$userStatement){
			return $this->error("The userStatement with {$userStatement_id} doesn't exist", 404);
		}

		$transactions = $userStatement->transactions;
		return $this->success($transactions, 200);
	}

	public function store(Request $request, $userStatement_id){
        $userStatement = UserStatement::find($userStatement_id);
        if(!$userStatement){
			return $this->error("The userStatement with {$userStatement_id} doesn't exist", 404);
		}

		$this->validateRequest($request);

		//custom validation;
        if ($request->get('transaction_type') === 'D' && $userStatement->balance<$request->get('amount')) {
            $this->validate($request, ['amount'=>'numeric|required|between:1,'.$userStatement->balance], ['between'=>'Insufficient funds']);
        }
        $transaction = DB::transaction(function() use ($request, $userStatement) {

            //locking
            DB::select("SELECT * FROM user_statements where id = ". $userStatement->id." FOR UPDATE");

            $user = User::find($userStatement->user_id);

            $bonus = ($request->get('transaction_type') === 'C' && $userStatement->bonus_status == 2? $request->get('amount')* $user->bonus / 100 : 0);

            $transaction = Transaction::create([
                'amount' => $request->get('amount'),
                'transaction_type' => $request->get('transaction_type'),
                'user_id'=> $this->getUserId(),
                'bonus' => $bonus,
                'user_statement_id'=> $userStatement->id
            ]);


            $userStatement->balance += ($request->get('transaction_type')==='D' ? -1 * abs($request->get('amount')) : abs($request->get('amount')));
            if ($request->get('transaction_type')==='D') {
                $userStatement->withdrawals += 1;
            }else {
                $userStatement->deposits += 1;
            }
            $userStatement->bonus_balance += $bonus;
            $userStatement->bonus_status = ($userStatement->bonus_status == 2)? 0 : ($userStatement->bonus_status+1);;

            $userStatement->save();

           return $transaction;
        });


		return $this->success("The transaction with id {$transaction->id} has been created and assigned to the userStatement with id {$userStatement_id}", 201);
	}

	public function update(Request $request, $userStatement_id, $transaction_id){

		return $this->success("The transaction with with id {$transaction->id} has been updated", 200);
	}

	public function destroy($userStatement_id, $transaction_id){
		
		$transaction 	= Transaction::find($transaction_id);
		$userStatement 		= UserStatement::find($userStatement_id);

		if(!$transaction || !$userStatement){
			return $this->error("The transaction with {$transaction_id} or the userStatement with id {$userStatement_id} doesn't exist", 404);
		}

		if(!$userStatement->transactions()->find($transaction_id)){
			return $this->error("The transaction with id {$transaction_id} isn't assigned to the userStatement with id {$userStatement_id}", 409);
		}

		$transaction->delete();

		return $this->success("The transaction with id {$transaction_id} has been removed of the userStatement {$userStatement_id}", 200);
	}

	public function validateRequest(Request $request){

		$rules = [
			'amount' => 'numeric|required|min:1',
            'transaction_type'=>'in:C,D'
		];

        $this->validate($request, $rules);
	}

	public function isAuthorized(Request $request){

		$resource  = "transactions";
		$transaction   = Transaction::find($this->getArgs($request)["transaction_id"]);

		return $this->authorizeUser($request, $resource, $transaction);
	}
}
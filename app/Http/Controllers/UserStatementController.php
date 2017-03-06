<?php

namespace App\Http\Controllers;

use App\UserStatement;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserStatementController extends Controller{

	public function __construct(){

		$this->middleware('oauth', ['except' => ['index', 'show']]);
		$this->middleware('authorize:' . __CLASS__, ['except' => ['index', 'show', 'store', 'adminIndex']]);
	}

	public function index(){
	    $userStatements = UserStatement::all()->where('user_id',1);
		return $this->success($userStatements, 200);
	}

    public function adminIndex(){

        $userStatements = UserStatement::all();
        return $this->success($userStatements, 200);
    }

	public function store(Request $request){

		$this->validateRequest($request);

		$userStatement = UserStatement::create([
					'amount' => 0,
					'currency'=> $request->get('currency'),
					'balance' => 0,
					'bonus_balance' => 0,
					'bonus_status' => 0,
					'deposits' => 0,
					'withdrawals' => 0,
                    'user_id' => $this->getUserId()

				]);

		return $this->success("The userStatement with with id {$userStatement->id} has been created", 201);
	}

	public function show($id){

		$userStatement = UserStatement::find($id);

		if(!$userStatement){
			return $this->error("The userStatement with {$id} doesn't exist", 404);
		}

		return $this->success($userStatement, 200);
	}

	public function update(Request $request, $id){

		$userStatement = UserStatement::find($id);

		if(!$userStatement){
			return $this->error("The userStatement with {$id} doesn't exist", 404);
		}

		$this->validateRequest($request);

		$userStatement->title 		= $request->get('title');
		$userStatement->content 		= $request->get('content');
		$userStatement->user_id 		= $this->getUserId();

		$userStatement->save();

		return $this->success("The userStatement with with id {$userStatement->id} has been updated", 200);
	}

	public function destroy($id){

		$userStatement = UserStatement::find($id);

		if(!$userStatement){
			return $this->error("The userStatement with {$id} doesn't exist", 404);
		}

		// no need to delete the comments for the current userStatement,
		// since we used on delete cascase on update cascase.
		// $userStatement->comments()->delete();
		$userStatement->delete();

		return $this->success("The userStatement with with id {$id} has been deleted along with it's transactions", 200);
	}

	public function validateRequest(Request $request){

		$rules = [
			'title' => 'required', 
			'content' => 'required'
		];

		$this->validate($request, $rules);
	}

	public function isAuthorized(Request $request){

		$resource = "userStatements";
		$userStatement     = UserStatement::find($this->getArgs($request)["user_statement_id"]);

		return $this->authorizeUser($request, $resource, $userStatement);
	}
}
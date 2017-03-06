<?php 

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{

	public function __construct(){

		$this->middleware('oauth', ['except' => ['index', 'show', 'userStatements']]);
		$this->middleware('authorize:' . __CLASS__, ['except' => ['index', 'show']]);
	}

	public function index(){

		$users = User::all();
		return $this->success($users, 200);
	}

	public function store(Request $request){

		$this->validateRequest($request);

		$user = User::create([
					'email' => $request->get('email'),
					'gender' => $request->get('gender'),
					'firstname' => $request->get('firstname'),
					'lastname' => $request->get('lastname'),
					'country' => $request->get('country'),
					'bonus' => $request->get('bonus'),
					'password'=> Hash::make($request->get('password'))

				]);

        $userStatement = UserStatement::create([
                    'amount' => 0,
                    'currency'=> $request->get('currency'),
                    'balance' => 0,
                    'bonus_balance' => 0,
                    'bonus_status' => 0,
                    'deposits' => 0,
                    'withdrawals' => 0,
                    'user_id' => $user->id,

                 ]);

		return $this->success("The user with with id {$user->id} has been created", 201);
	}

	public function show($id){

		$user = User::find($id);

		if(!$user){
			return $this->error("The user with {$id} doesn't exist", 404);
		}

		return $this->success($user, 200);
	}

	public function update(Request $request, $id){

		$user = User::find($id);

		if(!$user){
			return $this->error("The user with {$id} doesn't exist", 404);
		}

		$this->validateRequest($request);

		$user->email 		= $request->get('email');
		$user->password 	= Hash::make($request->get('password'));

		$user->save();

		return $this->success("The user with with id {$user->id} has been updated", 200);
	}

	public function destroy($id){

		$user = User::find($id);

		if(!$user){
			return $this->error("The user with {$id} doesn't exist", 404);
		}

		$user->delete();

		return $this->success("The user with with id {$id} has been deleted", 200);
	}

	public function validateRequest(Request $request){

		$rules = [
			'email' => 'required|email|unique:users', 
			'password' => 'required|min:6',
			'lastname' => 'required|min:6',
			'firstname' => 'required|min:6',
			'gender' => 'required|in:M,F',
            'country' => 'required|string|size:2',
            'bonus' => 'float|min:0',
		];

		$this->validate($request, $rules);
	}

	public function isAuthorized(Request $request){

		$resource = "users";
		// $user     = User::find($this->getArgs($request)["user_id"]);

		return $this->authorizeUser($request, $resource);
	}
}
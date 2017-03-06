<?php
/**
 * Created by PhpStorm.
 * User: scorpse
 * Date: 06-Mar-17
 * Time: 2:23 AM
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function __construct(){

        $this->middleware('oauth', ['except' => ['index','generalReport']]);
        $this->middleware('authorize:' . __CLASS__, ['except' => ['index', 'show', 'generalReport']]);
    }

    public function generalReport(Request $request) {



        if ($request->get('start_date') && $request->get('end_date')) {
            $this->validateRequest($request);
            $interval =  "WHERE DATE(t.created_at) BETWEEN '".$request->get('start_date')."'"." AND '".$request->get('end_date')."'";
        }else {
            $interval = "WHERE DATE(t.created_at) > (NOW() - INTERVAL 7 DAY)";
        }

        $rows = DB::select("
            SELECT
              DATE(t.created_at) as `Date`,
              u.country as `Country`,
              count(DISTINCT u.id) as `Unique Customers`,
              sum(case when t.transaction_type = 'D' then 1 else 0 end) as `No of Deposits`,
              sum(case when t.transaction_type = 'D' then t.amount else 0 end) as `Total Deposit Amount`,
              sum(case when t.transaction_type = 'C' then 1 else 0 end) as `No of Withdrawals`,
              sum(case when t.transaction_type = 'C' then t.amount else 0 end) as `Total Withdrawal Amount`
            FROM users u
              LEFT JOIN transactions t on t.user_id = u.id
              left join user_statements s on s.user_id = u.id ".

           $interval

            ." group by u.country,DATE(t.created_at) 
        
        
        ");

        return $this->success($rows, 200);
    }

    public function validateRequest(Request $request){

        $rules = [
            'start_date' => 'date',
            'end_date' => 'date',

        ];

        $this->validate($request, $rules);
    }
}
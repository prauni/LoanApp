<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Loan;
use App\Models\LoanRepayment;
use Illuminate\Support\Facades\Gate;

class LoanController extends Controller
{
    protected $user;

    public function list(){

        $list = Loan::with(['user','repayment']);
        if(!Auth::user()->is_admin){
            $list = $list->where('user_id', Auth::user()->id);
        }
        $list = $list->get()->toArray();
        return response()->json(['message' => 'Applied loan list', 'result' => $list], 200);
    }

    public function application(Request $request){
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1|max:10000|gt:tender',
            'tender' => 'required|numeric|min:1|max:60|lt:amount'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $insData['user_id'] = Auth::user()->id;
        $insData['amount']  = $request->amount;
        $insData['tender']  = $request->tender;
        $res = Loan::create($insData);
        
        return response()->json(['message'=>'Loan application created successfully!','details'=>$res]);
    }

    public function updateStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'loan_id' => 'required|numeric|min:1',
            'status' => 'required|numeric|between:1,2',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        if(!Auth::user()->is_admin){
            return response()->json(['error'=>true, 'message'=>'Admin login required.']);
        }

        $loanDetails = Loan::find($request->loan_id);
        if(empty($loanDetails)){
            return response()->json(['error'=>true, 'message'=>'Loan details not found.']);
        }

        $loanDetails->update(['status'=>$request->status]);
        return response()->json(['error'=>false, 'message'=>'Loan status updated successfully!']);
    }

    public function repayment(Request $request, LoanRepayment $repayment){
        $validator = Validator::make($request->all(), [
            'loan_id' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $loan_id = $request->loan_id;
        $amount  = $request->amount;
        $whereCond['id'] = $loan_id;
        if(!Auth::user()->is_admin){
            $whereCond['user_id'] = Auth::user()->id;
        }
        $loanDetails = Loan::where($whereCond)->get();
        
        if(empty($loanDetails[0])){
            return response()->json(['error'=>true, 'message'=>'Loan details not found.']);
        }
        $loanDetails = $loanDetails[0];
        
        if($loanDetails->status != 1){
            $error_msg = '';

            if($loanDetails->status == 0){
                $error_msg = 'Loan is waiting for approval.';
            }
            else if($loanDetails->status == 2){
                $error_msg = 'Loan application rejected.';
            }
            else if($loanDetails->status == 3){
                $error_msg = 'Loan\'s repayment completed.';
            }
            return response()->json(['error'=>true, 'message'=>$error_msg], 400);
        }

        
        $repaymentAmount = LoanRepayment::selectRaw("SUM(amount) as total_paid_amount")->where('loan_id', $loan_id)->groupBy('loan_id')->get()->toArray();
        $totalPaidAmount = isset($repaymentAmount[0]['total_paid_amount'])?$repaymentAmount[0]['total_paid_amount']:0;
        $remainingAmount = $loanDetails->amount - $totalPaidAmount;
        
        if($remainingAmount < $request->amount){
            $error_msg = 'Loan\'s max repayment amount is '.$remainingAmount;
            return response()->json(['error'=>true, 'message'=>$error_msg], 400);
        }

        $insData['loan_id'] = $loan_id;
        $insData['amount']  = $amount;
        $res = LoanRepayment::create($insData);

        if(($totalPaidAmount + $request->amount) >= $loanDetails->amount){
            $loanDetails->update(['status'=>3]);
        }

        return response()->json(['message'=>'Loan repayment completed successfully!','details'=>$res]);
    }
}

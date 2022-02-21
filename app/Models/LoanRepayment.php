<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    use HasFactory;
    protected $table    = 'loan_repayments',
            $primaryKey = 'id',
            $fillable   = ['loan_id', 'amount'];
    
    public function loan(){
        return $this->belongsTo(Loan::class);
    }
}

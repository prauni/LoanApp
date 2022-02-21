<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $table    = 'loans',
            $primaryKey = 'id',
            $fillable   = ['user_id', 'amount', 'tender', 'status'];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function repayment(){
        return $this->hasMany(LoanRepayment::class);
    }
}

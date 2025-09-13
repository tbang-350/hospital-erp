<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id', 'period', 'basic_salary', 'allowances', 
        'deductions', 'net_pay', 'paid_at', 'meta'
    ];

    protected $casts = [
        'paid_at' => 'date',
        'meta' => 'array',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_pay' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStatusAttribute()
    {
        return $this->paid_at ? 'paid' : 'pending';
    }

    public function calculateNetPay()
    {
        $this->net_pay = $this->basic_salary + $this->allowances - $this->deductions;
        return $this->net_pay;
    }
}

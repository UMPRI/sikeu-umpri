<?php 

namespace App\UseCases\Deposit;
use App\Repository\Deposit\DepositRepository;

class DepositUseCase{
    public function GetDeposit($registerNumber){
        $repo = new DepositRepository();
        return $repo->GetDeposit($registerNumber);
    }
}
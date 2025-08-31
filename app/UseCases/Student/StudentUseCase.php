<?php 

namespace App\UseCases\Student;

use App\Repository\Student\StudentRepository;
use ReallySimpleJWT\Token;
use ReallySimpleJWT\Parse;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Decode;

class StudentUseCase{
    private $secret = "sec!ReT423*&";
    public function Authentication($registerNumber, $password){

        $stdRep = new StudentRepository();
        $auth = $stdRep->Authentication($registerNumber, $password);

        return $auth;
    }

    public function GetToken($registerNumber, $password){
        $stdRep = new StudentRepository();

        $res = [];
        $res['status'] = true;
        $res['message'] = "Success";
        $res['data'] = [];

        // TODO Check Auth
        if(!$this->Authentication($registerNumber, $password)){
            $res['status'] = false;
            $res['message'] = "Unauthorized";

            return $res;
        }

        $now = new \DateTimeImmutable();

        $payload = [
            'std' => $registerNumber,
            'exp' => $now->modify('+60 minutes')
        ];
        try{
            $token = Token::customPayload($payload, $this->secret);

            $res['data']['token'] = $token;
            $res['data']['token_type'] = "Bearer";
            $res['data']['expired'] = $now->modify('+60 minutes');
        }catch(\Exception $e){
            $res['status'] = false;
            $res['message'] = "Internal Server Error";
        }
        
        return $res;
    }

    public function TokenValidation($token, $registerNumber){
        $res = [];
        $res['status'] = true;
        $res['message'] = "Success";

        $tokenString = substr($token, 7);
        // dd($tokenString);

        try{
            $validation = Token::validate($tokenString, $this->secret);

            if(!$validation){
                $res['status'] = false;
                $res['message'] = "Access Token Invalid";
            }else{

                $jwt = new Jwt($tokenString, $this->secret);
                $parse = new Parse($jwt, new Decode());
                $parsed = $parse->parse();

                // Return the token payload claims as an associative array.
                $claims = $parsed->getPayload();

                if(Date('Y-m-d H:i:s',strtotime($claims['exp']['date'])) < Date('Y-m-d H:i:s')){
                    $res['status'] = false;
                    $res['message'] = "Access Token Expired";        
                }
            }
        }catch(\Exception $e){
            $res['status'] = false;
            $res['message'] = "Access Token Invalid. message : ".$e->getMessage();
        }

        return $res;
    }

    function GetBills($id, $billType){
        $stdRep = new StudentRepository();

        $res = [];
        $res['status'] = true;
        $res['message'] = "Success";
        $res['data'] = [];

        try{

            switch($billType){
                case 3:
                    $std = $stdRep->GetById($id);
                    if($std == null){
                        $res['status'] = false;
                        $res['message'] = "Student not found";
                        break;
                    }

                    $res['data'] = $stdRep->GetNonKRSBills($std->Register_Number);

                    break;
                case 4:
                    $std = $stdRep->GetById($id);
                    if($std == null){
                        $res['status'] = false;
                        $res['message'] = "Student not found";
                        break;
                    }

                    $res['data'] = $stdRep->GetKRSBills($std->Register_Number);

                    break;

                default:
                    $res['status'] = false;
                    $res['message'] = "Bill type is not valid";

                    break;
            }

        }catch(\Exception $e){
            $res['status'] = false;
            $res['message'] = "".$e->getMessage();
        }

        return $res;
    }
}

?>
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserModel;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
public function processForm($type){
          $user = new UserModel();
          $name = $this->postVar('name');
          $email = $this->postVar('email');
          $user_id = $_SESSION['user_id'];
          
          
        if($type=='signup'){
          $password = $this->postVar('password');
          $confirm_pass = $this->postVar('confirm_password');
          
           if(!$this->validateNameSpace($name)){
               $this->Ajax_responseJson('error', 'Invalid name');
           }
           if(!$this->validateEmail($email)){
               $this->Ajax_responseJson('error', 'Invalid Email address');
           }
           if($this->objNameExistsInDB('email','users', $email)){
               $this->Ajax_responseJson('error','Email already exists. Please enter anoter email');
           }
           
           
          if(empty($password)){
             $this->Ajax_responseJson('error', 'Invalid Password');  
          }
          if($password !== $confirm_pass){
              $this->Ajax_responseJson('error', 'Password and confirm Password do not match');   
          }
          
          
          $user->name = $name;
          $user->email = $email;
          $user->password = $this->generateSaltedHash(trim($password));
          $user->save();
          
           $this->Ajax_responseJson('done', 'You have been registered successfully'); 
        }
        if($type=='profile-upd'){
           $user_id = $_SESSION['user_id'];
           
           if(!$this->validateNameSpace($name)){
                $this->Ajax_responseJson('error','Invalid Name');
            }
            if(!$this->validateEmail($email)){
                $this->Ajax_responseJson('error','Invalid Email Id');
            }
            
            if(!$this->validatePhone($phone)){
                $this->Ajax_responseJson('error','Invalid Phone Number');
            }
            
            if(!empty($phone2) && $phone2!='none'){
            if(!$this->validatePhone($phone2)){
                $this->Ajax_responseJson('error','Invalid Optional phone number');
            }
        }else{
                $phone2 = $this->selectObject('phone2','users', 'user_id', $user_id);
            }
        
        if(!$this->validateAddress($address)){
            $this->Ajax_responseJson('error','Please enter a valid Address');
        }
        if(!empty($date_of_birth) && $date_of_birth!=='none'){
           if(!explode('/',$date_of_birth)){
              $this->Ajax_responseJson('error', 'Invalid Date of Birth'); 
           }
          }else{
              $date_of_birth = 'none';
          }
        $update = $this->updateById('users',['name'=>$name,'email'=>$email,
            'phone'=>$phone,'phone2'=>$phone2,
            'address'=>$address,'date_of_birth'=>$date_of_birth], ['user_id','=',$user_id]);
        
            $this->Ajax_responseJson('done', 'Update was Successfull');
        
      }
        if($type=='change-pass'){
            
        }
        if($type=='img-upload'){
           $img_name = $this->fileName('img');
            $img_size = $this->fileSize('img');
            $img_temp = $this->fileTemp('img');
            //$img_dim = $this->fileDimention('img');
            $img_type = $this->fileType('img');
            
              if(!empty($img_name)){
                   if(!in_array($img_type, $this->allowed_image_types)){
                   $this->Ajax_responseJson('error', " Image contains invalid extension");
                   }
                   
                   
                   $exp_fv = explode('.', $img_name);
            $new_img_name = $user_id.'.'.end($exp_fv);
            $img_destination = $this->imgUploadPath('upload_folder', $new_img_name);
            $img_path = 'upload_folder'.'/'.$new_img_name;
            
            
            if(!file_exists($img_destination)){
                if(!move_uploaded_file($img_temp, $img_destination)){
                $this->Ajax_responseJson('error','Unable to upload image file. Please try again');
               }
            }
                $update = $this->updateById('users',['img_path'=>$img_path], ['user_id','=',$user_id] );
                $this->Ajax_responseJson('done', 'Image was uploaded successfully');
               }else{
                   $this->Ajax_responseJson('error', 'you have not uploaded any image');
               }
        }
    }
    
    public function getUserData($user_id) 
    {
      $user = $this->selectAllById('users', ['user_id','=',$user_id]);
      return array('name'=>$user->name,'email'=>$user->email,'img_path'=>$user->img_path);
    }
    
    public function processUserLogin(){
      $email = filter_input(INPUT_POST, 'email');
      $pass = filter_input(INPUT_POST, 'password');
      $get_user_pass = $this->getUserPassword('users','email',$email);
      $password = $this->getSaltedHash($pass, $get_user_pass);
        if(!$this->authenticateUser($email, $password))
        {
          $this->Ajax_responseJson('error', 'Invalid Login Details');
        } else {
            
            $_SESSION['user_id']=$this->authenticateUser($email, $password,'id');
            $this->Ajax_responseJson('done','panel');
        }
    }
    
    public function processUserLogout(){
      unset($_SESSION['user_id']);
      $this->Ajax_responseJson('done', '');
    }
    
    public function getQuestions($param) {
      
    }
    
    public function UserPanel(){
        $user_id = $_SESSION['user_id'];
        $quests = $this->selectAll('questions');
        return view('panel',['questions'=>$quests,'name'=>$this->getUserData($user_id)['name'],
            'img_path'=>$this->getUserData($user_id)['img_path']]);
    }
    
    }
 
    
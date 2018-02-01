<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use App\UserModel;
use App\AdminModel;
 
class Controller extends BaseController
{

use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

private $_saltLength = 7;    
public $allowed_image_types = array ("image/jpeg","image/pjpeg","image/png","image/gif","image/jpg");
public $MAX_UPLOAD_SIZE = 2000000;

public function imgUploadPath($sub_folder,$img_name) {
    if(!file_exists(base_path('upload_img/'))){
        mkdir(base_path('upload_img/'));
    }
 return base_path('upload_img/'.$img_name);   
}

public function generateRandomStr($max) {
        return substr( md5(rand()), 0, $max);
}
public function moveUploadedFile($filename, $destination) {
   return move_uploaded_file($filename, $destination);
}

public function fileFieldName($field_name){
  return $_FILES["$field_name"];        
}
function fileName($field_name) {
   return $this->fileFieldName($field_name)['name'];
}
function fileType($field_name) {
   return $this->fileFieldName($field_name)['type'];
}
function fileSize($field_name) {
   return $this->fileFieldName($field_name)['size'];
}
function fileTemp($field_name) {
   return $this->fileFieldName($field_name)['tmp_name']; 
}
function fileDimention($field_name) {
    if($this->fileName($field_name)){
  return getimagesize($this->fileTemp($field_name));
    }else{
        return null;
    }  
}
function getImgDimention($var,array $dim) {
    if($var=='height'){
      $obj =  $this->selectObject('img_upload_dim', 'settings', 'settings_id', '1');
    }
    if($var=='width'){
     $obj =  $this->selectObject('img_upload_dim', 'settings', 'settings_id', '1');   
    }
    if(is_null($obj)){
       $exp = array('width'=>$dim[0],'height'=>$dim[1]);
        return $exp[$var];
    } else{
       $ex = explode(',',$obj);
       $exp = array('width'=>$ex[0],'height'=>$ex[1]);
        return $exp[$var];
    }
}

public function setRequiredField() {
 $str = <<<str
         <span class="text-danger">*</span>
str;
 return $str;
}

public function selectObject($var,$table,$search,$value,$type=null){
    if(is_null($type)){
        $type='value';
    }
    $obj = DB::table($table)->where($search, $value)->$type($var);
    return $obj;
}

public function selectAllById($table, array $where) {
    $obj = DB::table($table)->where([$where])->first();
    return $obj;
}

public function selectAll($table,$type=null){
    if(is_null($type)){
        $type='get';
    }
        $obj = DB::table($table)->$type(); 
    return $obj;
}

function selectByOffset($table,$limit_type,$offset=0) {
    $limit= $this->NavLimit($limit_type);
    $obj = DB::table($table)
                ->offset($offset)
                ->limit($limit)
                ->get();
    return $obj;
}

public function updateById($table,array $upd, array $where) {
   $obj = DB::table($table)
            ->where([$where])
            ->update($upd);
   if($obj){
       return true;
   } else {
   return false;    
   }
}

public function deleteById($table, array $where) {
   $obj = DB::table($table)
            ->where([$where])
            ->delete();
   if($obj){
       return true;
   } else {
   return false;    
   }
}

protected function NavLimit($type) {
    $session_limit = isset($_SESSION['nav_session'])?$_SESSION['nav_session']:null;
    if($type=='minor'){
        if(!is_null($session_limit)){
            $nav_limit=$session_limit;
        } else {
            $nav_limit=5;
        }
    }
    if($type=='major'){
        if(!is_null($session_limit)){
            $nav_limit=$session_limit;
        } else {
            $nav_limit=20;
        }
    }
        return $nav_limit;
    
}

public function navObject($offset,$table,$nav='minor') {
    $nav_limit = $this->NavLimit($nav);
    $hidden = csrf_field();
        $total_rows = $this->selectAll($table,'count');
        $btn_id = $table.'-nav-btn';
        $action = $table.'-nav';
        $form =  $table.'-form';
        $str = "<p>";
        $str .= "<ul id=\"nav\" class=\"pager\"
    opacity: 0.5;
    border-radius: 50%;\">";
         if($offset > 0 && $offset + $nav_limit >= $total_rows || $offset/$nav_limit >0)
        {
            ($offset + $nav_limit >= $total_rows) ? $str .= $total_rows ." of ".$total_rows." $table\t":'';
            $str .= "<li>"
                    . " <form style=\"display:inline\" class=\"$form\" action=\"manage-$table\" method=\"post\">"
                    . "{$hidden}"
                    . "<button onclick=\"formProcessorNavWithCallbacks('.$form','manage-$table','#$table-callback')\" type=\"submit\" class=\"btn-sm\"  > << previous entries"
                    . "<input type=\"hidden\" name=\"offset\" value=\"".($offset-$nav_limit)."\">"
                    . "<input type=\"hidden\" name=\"action\" value=\"$action\"> "
                    . "</button></form></li> &nbsp;"
                    . "";
        } 
             if($offset + $nav_limit < $total_rows)
        {
            $str .= $offset+$nav_limit." of ".$total_rows." $table\t";
            $str .= "<li>"
                    . "<form style=\"display:inline\" class=\"$form\" action=\"manage-$table\" method=\"post\">"
                    . "{$hidden}"
                    . "<button onclick=\"formProcessorNavWithCallbacks('.$form','manage-$table','#$table-callback')\" type=\"submit\" class=\"btn-sm\"  > >> next entries "
                    . "<input type=\"hidden\" name=\"offset\" value=\"".($offset+$nav_limit)."\">"
                    . "<input type=\"hidden\" name=\"action\" value=\"$action\">"
                    . "</button></form></li> &nbsp;"
                    . "";
        }
        
       
        $str .= "</ul>";
        $str .= "</p>";
        return $str;  
}

    function validateName($value) 
    {
        return (!preg_match('/^[a-zA-Z]+$/i', $value))?false:true;
    }
      function validateNameSpace($value) 
    {
        return (!preg_match('/^[a-zA-Z ]+$/i', $value))?false:true;
    }
    
     function validateFile($value) 
    {
        $div = explode('.', $value);
        $value1 = $div[0]; 
        return (!preg_match('/^[_a-z0-9-]{1,15}$/i', $value1))?false:true;
    }
      function validatePassword($value) 
    { 
        return (!preg_match('/^[@_a-z0-9-]{1,15}$/i', $value))?false:true;
    }
   
     function validateEmail($value) 
    { 
       return (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $value))?false:true;
    }
    
     function validateUrl($value) 
    {
        return  (!preg_match("/\b(?:(?:https?|ftp|http):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$value))?false:true;
    }
    
     function validatePhone($value) 
    { 
        return (!preg_match('/^\+?[0-9-]+$/', $value)) ? false : true; 
    }
     function validateuri($value) 
    { 
        return (!preg_match('/^[0-9]+$/', $value)) ? false : true; 
    }
     function validateDigits($value){ 
        return self::validateuri($value);
    }

       function validateAddress($value) 
    {
        return (!preg_match('/^([a-zA-Z ,_\-\'\.\"0-9])+$/', $value))?false:true;
    }
    
     function validateSelect($value) 
    {
        return ( $value =='0' || $value=null)?false:true;
    }
    function Ajax_responseJson($msg_type, $text) {
    print json_encode (array('type'=>$msg_type, 'text'=>$text));
    exit; 
    }
    
    function postVar($field_name) {
      return filter_input(INPUT_POST, $field_name);
    }
    
    function rawPostVar($field_name){
       return !empty($_POST[$field_name])?$_POST[$field_name]:$this->postVar($field_name); 
    }
    
    function getVar($field_name) {
      return filter_input(INPUT_GET, $field_name);
    }
    function sessionVar($session_name) {
      return $_SESSION["$session_name"];
      
    }
    
    public function objNameExistsInDB($col,$table,$val) {
      $obj = DB::table($table)->where($col, $val)->value($col);
      if(is_null($obj)){
          return false;
      } else {
          return true;
      }
    
      }
      
      public function ajaxResponseCallback($type,$text,$callback,$mass_load=null) {
       print json_encode(array('type'=>$type,'text'=>$text,'content'=>$callback, 'mass_load'=>$mass_load));
       exit;
      }
      
    protected function insertLoginDetails($table,$username,$password,$id_field,$obj_id){
       $query = "INSERT INTO $table (username,password,$id_field)values('$username','$password','$obj_id')";
       $this->safeQuery($query);
   }
    
public function generateSaltedHash($string, $salt=NULL) 
{ 
return $this->getSaltedHash($string, $salt); 
} 

public function getSaltedHash($string, $salt=NULL) 
{ 
/* 
* Generate a salt if no salt is passed 
*/ 
if ( $salt==NULL ) 
{ 
$salt = substr(md5(time()), 0, $this->_saltLength); 
} 
/* 
* Extract the salt from the string if one is passed 
*/ 
else 
{ 
$salt = substr($salt, 0, $this->_saltLength); 
}
return $salt . sha1($salt . $string); 
} 

public function getUserPassword($table,$search,$username){
        $result = $this->selectObject('password', $table, $search, $username);
    if($result)
    {
        return $result;
    } else {
        return null;
    }
    }
    
    public function authenticateUser($email,$password,$type=null){
      if($type==null){
     $user = DB::select("select user_id from users where email=? and password=?",[$email,$password]);
      if($user){
          return true;
      } else {
         return null; 
      }
      }
      if($type=='id'){
         $user = UserModel::where('email','=',$email)->first();
      if($user){
          return $user->user_id;
      } else {
         return null; 
      } 
      }
    }
    
    public function authenticateAdmin($username,$password,$type=null) {
      $admin = AdminModel::where('username','=',$username)->where('password','=',$password)->first();
      if($type==null){
      if($admin){
          return true;
      } else {
         return false; 
      }
      }
      
       if($type=='id'){
         $admin = AdminModel::where('username','=',$username)->first();
      if($admin){
          return $admin->admin_id;
      } else {
         return null; 
      } 
      }
    }
    
    public function getDate() {
        return  date('d-m-Y H:i:s');
    }
    
    public function decodeObj($code) {
       if($code==0){
           $str =<<<str
          <span class="btn btn-danger">Canceled</span>         
str;
       } 
       if($code==1){
           $str =<<<str
          <span class="btn btn-success">Approved</span>         
str;
       } 
       if($code==2){
           $str =<<<str
          <span class="btn btn-warning">Pending</span>         
str;
       }
       return $str;
    }
    
    public function userTimer($location,$id){
    if(isset($_SESSION['timer']) && time() - $_SESSION['timer'] > 10*60)
  {
        unset($_SESSION[$id]);
        unset($_SESSION['timer']);
    ?>
    <script>
        alert("Your Session is Over. Please Login to continue");  
    window.location = "<?=$location?>";
    </script>
   <?php
  }
     else {
          $_SESSION['timer']=time();
          ?>
        <script>
           window.setTimeout(function(){
             window.location.href = window.location;     
             }, 19000000);
       </script>
        <?php
    }
}

}

<?php
class ErrorController extends Controller {
    public function Main(){
        $message    = isset(System::$Scope["error"]) ? System::$Scope["error"]->getMessage() : "Check if document is available or if URL is correct.\n";
            $code   = isset(System::$Scope["error"]) ? System::$Scope["error"]->getCode() : "404";
            $info   = isset(System::$Scope["error"]) ? "\n\nSource: \n".System::$Scope["error"]->getFile().":".System::$Scope["error"]->getLine() : false;
        $this->Data['title']    = "404";
        $this->Data['desc']     = "Requested page was not found";
        $this->Data['err']      = $message."\n\n\nCode: ".$code;
        if($info !== false) $this->Data['err'] .= $info;
        $this->setView("common","error");
        return $this->Execute();
    }
    public function MySQLError(){
        $this->Data['title']    = "404";
        $this->Data['desc']     = "Requested page was not found";
        $this->Data['err']      = "Requested controller '";
        $this->setView("common","error");
    }
}
?>

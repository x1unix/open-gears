<?php
 System::GetModel("helper");

class MainController extends Controller
{
  // Main entry point of each controller
  public function Main()
  {
    session_start();
    $this->SetView("index","main");
    $this->Data['title'] = "OpenGears Framework";
    $this->Data['description'] = "An lightweight PHP MVC framework";
    $this->Data['cdate'] = TimeStamp::ToString(time());

    return $this->Execute();
  }

  public function Submit()
  {

  }



 
}
?>

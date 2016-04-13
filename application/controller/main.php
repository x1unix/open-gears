<?php
class MainController extends Controller
{
  // Main entry point of each controller
  public function Main()
  {
    $this->SetView("index","main");
    $this->Data['title'] = "OpenGears Framework";
    $this->Data['description'] = "An lightweight PHP MVC framework";
    return $this->Execute();
  }

  public function Submit()
  {

  }



 
}
?>

<?php

class AboutController extends Controller
{
  // Main entry point of each controller
  public function Main()
  {
    $this->SetView("about","main");

    $this->AddToScope("pageTitle","About");

    $this->Data['content'] = file_get_contents(APPDATA."intro.html");
    $this->Data['archors'] = array(
      "intro"=>"Introduction",
      "requirements"=>"Requirements",
      "features"=>"Features",
      "struct"=>"Structure"
    );

    $this->Data['header'] = System::Invoke("common","header");
    $this->Data['footer'] = System::Invoke("common","footer");

    return $this->Execute();
  }

  public function Tutorial()
  {
    $this->SetView("about","main");

    $this->AddToScope("pageTitle","Tutorial");


    // Disable force compress output
    $this->CompressOutput = false;

    $this->Data['content'] = file_get_contents(APPDATA."tutorial.html");
    $this->Data['archors'] = array(
     "setup"=>"Setup",
      "helloWorld"=>"Hello World",
      "invoking"=>"Call controller inside the other",
      "mysql"=>"Working with MySQL",
      "models"=>"Models",
      "more"=>"More information"
    );

    $this->Data['header'] = System::Invoke("common","header");
    $this->Data['footer'] = System::Invoke("common","footer");

    return $this->Execute();
  }

  public function Api()
  {
    $this->SetView("about","main");

    // Disable force compress output
    $this->CompressOutput = false;

    $this->AddToScope("pageTitle","Tutorial");

    $this->Data['content'] = file_get_contents(APPDATA."tutorial.html");
    $this->Data['archors'] = array(
     "setup"=>"Setup",
      "helloWorld"=>"Hello World",
      "invoking"=>"Call controller inside the other",
      "mysql"=>"Working with MySQL",
      "models"=>"Models",
      "more"=>"More information"
    );

    $this->Data['header'] = System::Invoke("common","header");
    $this->Data['footer'] = System::Invoke("common","footer");

    return $this->Execute();
  }



 
}
?>
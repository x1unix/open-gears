<?php

class CommonController extends Controller
{
  public function Header()
  {
    $this->SetView("common","header");
    $this->Data['title'] = $this->Scope['pageTitle'];
    return $this->Execute();
  }

  public function Footer()
  {
    $this->SetView("common","footer");

    return $this->Execute();

  }


 
}
?>
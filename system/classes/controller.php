<?php
/**
 * ContentType Constants
 */
define("O_HTML","text/html");
define("O_TEXT"," text/plain");
define("O_JSON","application/json");


/**
 * OpenGears Controller Prototype
 * @version 0.6.0
 * @abstract Controller
 * @package opengears
 * @author Denis Sedchenko [sedchenko.in.ua]
 */
abstract class Controller
{
  protected $View;
  protected $hasView        = true;
  protected $DefaultCharset = "utf-8";
  protected $OutputFormat   = O_HTML;
  protected $Data           = array();
  protected $Before         = array();
  protected $After          = array();
  protected $Scope          = array();
  protected $Args           = array();
  protected $Output;

  protected $CompressOutput = MINIFY_OUTPUT;


  // Add a value to scope
  public function AddToScope($key,$value)
  {
    System::$Scope[$key] = $value;
  }

  
  // Compress output
  public function SanitizeOutput($buffer) {
    if(MINIFY_OUTPUT == false)
    {
      if($this->CompressOutput == false) return $buffer;
    }
    $search = array(
        '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
        '/[^\S ]+\</s',  // strip whitespaces before tags, except space
        '/(\s)+/s'       // shorten multiple whitespace sequences
    );

    $replace = array(
        '>',
        '<',
        '\\1'
    );

    $buffer = preg_replace($search, $replace, $buffer);

    return $buffer;
  }
  
  public function __construct($scope) {
    $GLOBALS['CurrentController']=get_class ($this);
    $this->Args = $scope['args'];
    $this->Scope = $scope;
  }

  /**
    * Add an array of controllers for pre-execution
    */
  public function AddBefore($_ctrl,$_act)
  {
    $a = array("controller"=>$_ctrl, "action"=>$_act);
    array_push($this->Before, $a);
    unset($a);
  }
  /**
    * Add an array of controllers to execute after the main controller executed
    */
  public function AddAfter($_ctrl,$_act)
  {
    $a = array("controller"=>$_ctrl, "action"=>$_act);
    array_push($this->After, $a);
    unset($a);
  }

  /**
    * Toggle a view for controller
    */
  public function SetView($mod,$view)
  {
    $this->View = $mod.DS.$view;
  }

  /**
    * Execute controller
    */
  public function Execute()
  {
    if($this->OutputFormat == O_JSON) return json_encode($this->Data);
    if($this->hasView == true && !file_exists(VIEWS."$this->View.php"))  throw new ViewNotFoundException("View not found: '".VIEWS."$this->View.php'", 1);
    
    if($this->hasView) extract($this->Data);
    ob_start();


    // Call subcontrollers before main view
    if(count($this->Before) > 0)
    {
      foreach ($this->Before as $id => $_ctrl) {
        System::Call($_ctrl["controller"],$_ctrl["action"]);
      }
    }

    // Call main view
    if($this->hasView) require(VIEWS."$this->View.php");

    // Call subcontrollers after main view
    if(count($this->After) > 0)
    {
      foreach ($this->After as $id => $_ctrl) {
        System::Call($_ctrl["controller"],$_ctrl["action"]);
      }
    }
    $this->Output = $this->SanitizeOutput(ob_get_contents());
    ob_end_clean();
    return $this->Output;

  }



}
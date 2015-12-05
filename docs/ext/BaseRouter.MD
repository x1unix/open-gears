# OpenGears Framework
**Documentation**

### Extensions / Base Router

#### Information
**Base Router** (EssentialRouter) - provides a base routing feature to call controller and action.

**Format:** (yourdomain.com/*controller*/*action*/*argument1*/*argument2*)

#### Controller arguments
Received arguments are available as array in **Controller->Args** field or in **System::Scope['arguments']**

**MyController.php** - Example

```
<?php
class MyController extends Controller
{
  public function Main() {
  // Print all args
    print_r($this->Args);
  }
}
?>
```

# OpenGears Framework
**Documentation**

### MySQL / Simple MySQL Driver

#### Information
**Simple MySQL Driver (SMSQLD)** provides a base functionality to work with database, such as **exceptions** and **formatted query results**.

### Initialisation

To access to database, we must define a **Database** instance as object

```
<?php
class usersController extends Controller
{

  public function Main()
  {
    $this->SetView("users","main");

    // Create a new object to access to DB
    // Name of table - users
    $database = new DataBase;
    $database->Base = "db";
    $database->Hostname = "localhost";
    $database->User = "root";
    $database->Password = "";
    
    }
}    
```

### Perform a query

To perform a MySQL query, you can use base **query** method, or 3 another to get formatted result.
If your query will have an error, you will get a **MySQLQueryException** exception.

```
try{

      // We can do a simple query, and also escape query string by the way
      $q = "SELECT * FROM `users` WHERE `music` = 'Metallica';";
      
      $q = $database->EscapeString($q);

      // Simple DB query
      $database->Query($q);

      // Get Rows with users as array of users
      $this->Data['users'] = $database->GetRows("SELECT * FROM `users`");

      // Get users count as integer
      $this->Data['count'] = $database->Count("users");

      // Get single row as array
      $this->Data['admin'] = $database->GetRow("SELECT * FROM `users` WHERE `id`='1'");
    }
    catch(MySQLQueryException $e)
    {
      //Catch error in mysql query
      die("Error in MYSQL syntax".$e);
    }


```

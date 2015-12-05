# OpenGears Framework
**Documentation**

### MySQL / Advanced MySQL Driver

#### Information
**Advanced MySQL Driver (AMSQLD)** driver is extended from **SMSQL** and has the same methods as **SMSQL**, but includes a lot of prefedined query methods.

From version **0.8.1**, advanced driver **is enabled by default** as main driver.

#### Features

#####Importing MYSQL dumps
You can directly import a dump file to your database.

If operation will failed, you will get **MySQLiImportException** or **MySQLQueryException**.

Example:
```
$database->Import('sqldump.sql');
```



#####Search a string in table
Search a word or part of expression in table.

```
// Search for users with GMail emails
$result = $database->Search("user_tables","user_email","@gmail.com");
```

To get a formatted array of rows, or single row, you can use:
```
// Rows
$result = $database->Find("user_tables","user_email","@gmail.com");
```
or:
```
// Row
$result = $database->FindSingle("user_tables","user_email","@gmail.com");
```


#####Selecting rows
Will return a **mysqli_result** object with selected items
```
// Get 10 users from group 10
$items = $database-Select('users',"*",false,
                          array(
                            "groupId"=>1
                          ),0,10);
$items = $database->ToRows($items);
```


###More

Another available database methods:
* **SelectAll**  - Select all rows as array
* **SelectOne**  - Select one row as array
* **Update**     - Update a row(s)
* **Insert**     - Insert a row
* **Delete**     - Delete a row
* **MakeDump**   - Make SQL dump

More information about them is available in PHPDoc format from your IDE.






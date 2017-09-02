Clear Framework
---------------

Clear framework is simple MVC Object oriented framework.

How to start
------------

1) Copy content of sandbox folder to your DOCUMENT_ROOT
2) Copy include folder to DOCUMENT_ROOT
3) Copy libs folder to DOCUMENT_ROOT

How to work with Database
--------------------------

In index PHP is initialized global cfDb instance of Db OBject.
Please specify your database connection details in config.php file.

For getting results from table use following sample.

$results = $cfDb->getResults ( "SELECT * FROM your_table;"); 

For inserting to table is used following sample.

$assoc = array('column_1' => 'val_1');
$cfDb->insert('your_table',$assoc);

For updating table records use following sample.

$assoc = array('column_1' => 'val_1');
$cfDb->update('your_table',$assoc,"yourid='$id'");

Working with database should be in Model part of MVC.
Create Class in models folder with public static functions as model operations.
See example

class Book {
  public static function newBook($bookName) {
    ...
  }

  public static function listBooks() {
    ...
  }

}

Functions are accessed after in Controler part of MVC as...
$books = Book::listBooks();
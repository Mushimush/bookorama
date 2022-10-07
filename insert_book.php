<html>

<head>
  <title>Book-O-Rama Book Entry Results</title>
</head>

<body>
  <h1>Book-O-Rama Book Entry Results</h1>
  <?php
  // create short variable names
  $isbn = $_POST['isbn'];
  $author = $_POST['author'];
  $title = $_POST['title'];
  $price = $_POST['price'];

  if (!$isbn || !$author || !$title || !$price) {
    echo "You have not entered all the required details.<br />"
      . "Please go back and try again.";
    exit;
  }

  /* If magic quotes are ON, php will automatically escape quotes coming 
     in POST or GET variables and automatically un-escape them 
     when pulling data out of a database for example. */

  // get_magic_quotes_gpc() is not supported and not in use in new version
  // do not need addslashes when using prepared statements



  // Syntax: mysqli(IP address of server, username, pwd, DB instance)
  @$db = new mysqli('localhost', 'root', '', 'f32ee');                 /*localhost,username,password, can create new user/pw in privilege */



  if (mysqli_connect_errno()) {
    echo "Error: Could not connect to database.  Please try again later.";
    exit;
  }

  // E.g The query can be 
  // insert into books values('12345', 'James', 'A Good Story', '47.50')
  // If use prepared statement method as below, no need to add slashes 

  $query = "insert into books values(?, ?, ?, ?)";                                             /*   ?  is a placeholder */
  $stmt = $db->prepare($query);                                                                /*place wtv in 49 to 47,treat wtv enter a pure text*/
  $stmt->bind_param('sssd', $isbn, $author, $title, $price);                                  /* sssd means string string string then float bind into command line 47 */
  $stmt->execute();

  // $stmt will be TRUE if insertion is successful
  if ($stmt) {
    echo  $stmt->affected_rows . " book inserted into database.";
  } else {
    echo "An error has occurred.  The item was not added.";
  }

  $db->close();
  ?>
</body>

</html>
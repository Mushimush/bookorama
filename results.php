<html>

<head>
  <title>Book-O-Rama Search Results</title>
</head>

<body>
  <h1>Book-O-Rama Search Results</h1>
  <?php
  // create short variable names
  $searchtype = $_POST['searchtype'];
  $searchterm = trim($_POST['searchterm']);  // trim whitespaces before and after

  if (!$searchtype || !$searchterm) {
    echo 'You have not entered search details.  Please go back and try again.';
    exit;
  }

  // addslashes - to escape character, i.e. to interpret \, you need to add \ in front
  $searchtype = addslashes($searchtype);
  $searchterm = addslashes($searchterm);

  // To cater to other HTMLs that may send in queries  
  switch ($searchtype) {
    case 'title':
    case 'author':
    case 'isbn':
      break;
    default:
      echo 'This is not a valid search type.  Please go back and try again.';
      exit;
  }


  @$db = new mysqli('localhost', 'root', '', 'f32ee');

  if (mysqli_connect_errno()) {
    echo 'Error: Could not connect to database.  Please try again later.';
    exit;
  }


  // Prepare the query like a template, CONCAT will concat %+$searchterm+%
  // E.g. select * from books where author like '%itt%'
  // e.g a sentence containing 'itt'
  $query = "select * from books where $searchtype like CONCAT('%',?,'%')";
  $stmt = $db->prepare($query);

  // replace ? with $searchterm, 's' means string value 
  $stmt->bind_param('s', $searchterm);
  $stmt->execute();

  // store results in $result
  $result = $stmt->get_result();
  $num_results = $result->num_rows;

  echo "<p>Number of books found: " . $num_results . "</p>";

  for ($i = 0; $i < $num_results; $i++) {
    $row = $result->fetch_assoc();
    // These not needed - htmlspecialchars - replace with html special chars
    echo "<p><strong>" . ($i + 1) . ". Title: ";
    echo htmlspecialchars(($row['title']));
    echo "</strong><br />Author: ";
    echo ($row['author']);
    echo "<br />ISBN: ";
    echo ($row['isbn']);
    echo "<br />Price: ";
    echo ($row['price']);
    echo "</p>";
  }

  $result->free();
  $db->close();

  ?>
</body>

</html>
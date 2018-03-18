<?php

/***********************************************************************

mySQL Database Access Class

Heavily based on the PHPLIB database access class available at 
http://phplib.netuse.de.

We use only a subset of the functions available in PHPLIB and their syntax is 
exactly the same.  This makes working with previous version of phpShop seamless
and keeps a consistent API for database access.  

Methods in the class are:

query($q) - Established connection to database and runs the query returning 
            a query ID if successfull.

next_record() - Returns the next row in the RecordSet for the last query run.  
                Returns False if RecordSet is empty or at the end.

num_rows()  -Returns the number of rows in the RecordSet from a query.

f($field_name) - Returns the value of the given field name for the current
                 record in the RecordSet.  

sf($field_name) - Returns the value of the field name from the $vars variable
                  if it is set, otherwise returns the value of the current
		  record in the RecordSet.  Useful for handling forms that have
		  been submitted with errors.  This way, fields retain the values 
		  sent in the $vars variable (user input) instead of the database
		  values.

p($field_name) - Prints the value of the given field name for the current
                 record in the RecordSet.

sp($field_name) - Prints the value of the field name from the $vars variable
                  if it is set, otherwise prints the value of the current
		  record in the RecordSet.  Useful for handling forms that have
		  been submitted with errors.  This way, fields retain the values 
		  sent in the $vars variable (user input) instead of the database
		  values.

$dsql = new DSQL();
$q = "select id from shop";
$dsql->query($q);
$dsql->next_record();
$id = $dsql->f(id);



$dsql = new DSQL();
$q = "select id from shop";
$dsql->query($q);
while($dsql->next_record()){

$id = $dsql->f(id);
echo $id;
}

************************************************************************/


class DSQL{
  
  var $lid = 0;             	// Link ID for database connection
  var $qid = 0;			// Query ID for current query
  var $row;			// Current row in query result set
  var $record = array();	// Current row record data
  var $error = "";		// Error Message
  var $errno = "";		// Error Number



  // Connects to DB and returns DB lid 
  // PRIVATE
  function connect() { 

    if ($this->lid == 0) {
      $this->lid = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_NAME); 
      if (!$this->lid) {
	$this->halt("connect(" . DB_HOST . "," . DB_USER . ",PASSWORD)  failed.");
      } 
      
      if (!@mysqli_select_db($this->lid,DB_NAME)) {
			$this->halt("�޷��������ݿ� ".DB_NAME);
			return 0;
      }
    }

	

	@mysqli_query($this->lid, "set character set 'utf8'");
	@mysqli_query($this->lid, "set names utf-8 ");
    return $this->lid;
  }


  // Runs query and sets up the query id for the class.
  // PUBLIC
  function query($q) {
    //echo $q;
    if (empty($q))
      return 0;
    
    if (!$this->connect()) {
      return 0; 
    }
    
    if ($this->qid) {
      @mysqli_free_result($this->qid);
      $this->qid = 0;
    }
    
    $this->qid = @mysqli_query( $this->lid,$q);
    $this->row   = 0;
    $this->errno = mysqli_errno($this->lid);
    $this->error = mysqli_error($this->lid);
    if (!$this->qid) {
      $this->halt("Invalid SQL: ".$q);
    }

    return $this->qid;
  }
  

  // Return next record in result set
  // PUBLIC
  function next_record() {

    if (!$this->qid) {
      $this->halt("next_record called with no query pending.");
      return 0;
    }
    
    $this->record = @mysqli_fetch_array($this->qid);
    $this->row   += 1;
    $this->errno  = mysqli_errno($this->lid);
    $this->error  = mysqli_error($this->lid);
    
    $stat = is_array($this->record);
    return $stat;
  }
  

  // Field Value
  // PUBLIC
  function f($field_name) {
    return stripslashes($this->record[$field_name]);
  }

  // Selective field value
  // PUBLIC
  function sf($field_name) {
    global $vars, $default;

    if ($vars["error"] and $vars["$field_name"]) {
      return stripslashes($vars["$field_name"]);
    } elseif ($default["$field_name"]) {
      return stripslashes($default["$field_name"]);
    } else {
      return stripslashes($this->record[$field_name]);
    }
  }                             

  // Print field
  // PUBLIC
  function p($field_name) {
      print stripslashes($this->record[$field_name]);
  }                             

  // Selective print field
  // PUBLIC
  function sp($field_name) {
    global $vars, $default;

    if ($vars["error"] and $vars["$field_name"]) {
      print stripslashes($vars["$field_name"]);
    } elseif ($default["$field_name"]) {
      print stripslashes($default["$field_name"]);
    } else {
      print stripslashes($this->record[$field_name]);
    }
  }                          

  // Returns the number of rows in query
  function num_rows() { 
    
    if ($this->lid) { 
      return @mysqli_numrows($this->qid); 
    } 
    else { 
      return 0; 
    } 
  }



  

  // Halt and display error message
  // PRIVATE
  function halt($msg) {
    $this->error = @mysqli_error($this->lid);
    $this->errno = @mysqli_errno($this->lid);

    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>MySQL Error</b>: %s (%s)<br>\n",
	   $this->errno,
	   $this->error);
    
    exit;

  }


  // Connects to DB and returns DB lid �������ݿ�����
  // PRIVATE
  function getConn() {

    if ($this->lid == 0) {
      $this->lid = mysqli_connect(DB_HOST, DB_USER, DB_PWD, DB_NAME);
      if (!$this->lid) {
          die('Connect Error (' . mysqli_connect_errno() . ') '
                  . mysqli_connect_error());
      }
      if (!@mysqli_select_db($this->lid,DB_NAME)) {
        $this->halt("�޷��������ݿ� ".DB_NAME);
        return 0;
      }
    }
    @mysqli_query($this->lid, "set character set 'utf8'");
    @mysqli_query($this->lid, "set names utf-8 ");
    return $this->lid;
  }

  var $query_prepare ;//����һ�� preparedstatment
  function getPstmt($q) {
    if (empty($q)){
      return 0;
    }

    //$this->lid = $this->getConn();
    if (!$this->getConn()) {
      return 0;
    }/**/
    $this->query_prepare = $this->lid->prepare($q);
    return $this->query_prepare;
  }

  function colsePstmt($closeConn) {// �رյ�ǰ preparedstatment; �Ƿ�رյ�ǰ���ݿ�����
    if (!$this->query_prepare){
      return 0;
    }
    $this->query_prepare->close();

    if (!$closeConn) {
    	return 0;
    }/**/
    if (!$this->lid){//�ر����ݿ�����
      return 0;
    }
    $this->lid->close();
  }


}
?>
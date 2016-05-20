ezMysql - easy to manage PHP-Msql
=================================

**What is ezMysql?**

It is a class that allows us to connect to a MySQL server and through this to manage a database.
Uses the PHP extension Mysqli (PHP 5, PHP 7). [more](http://php.net/manual/es/book.mysqli.php)

**What actions can I do?**
	
* Connection.
* Disconnection.
* Change database.
* Run querys.
* Retrieve the last id automatically generated.
* Formatting in an associative array results (protected method).

Installation
============

How to work with this class is very simple:

Manual Setup
------------

Include the ezMysql.class.php class within your project and name it as follows.
~~~php
require_once("./ezMysql.class.php");
~~~
`Note: You must place the route where the class within your project.`

Examples of Use
===============

In this example we will establish a connection to the MySQL server, create database "test" and the table "tb_test".

~~~php
require_once("./ezMysql.class.php");
try {
	$ezMysql = ezMysql::getconnection('localhost', 'root', '');
	$sql = "create database test";
	$ezMysql->Query($sql);
	$ezMysql->ChangeDb("test");

	$sql="CREATE TABLE tb_test
	(
	personID int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(personID),
	name varchar(15),
	lastname varchar(15),
	year int,
	phone int
	)";

	$ezMysql->Query($sql);
	$ezMysql->Disconnect();	

} catch (Exception $e) {
	die("Error: " . $e->getMessage());
}
~~~	

Important
=========

1. When the process is not completed or fails, the method will throw an exception, you should use "try catch".
2. For queries like "SELECT, SHOW, DESC" the method "Query" will return an associative array.
3. For queries like "INSERT, DELETE, UPDATE" the method "Query" will return an exception if the execution of the command is not completed
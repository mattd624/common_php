<?php


function connect_to_cnmaestro_pgdb() {
//Connect to cnmaestro db
  $conn = pg_connect('host=localhost port=5432 dbname=cnmaestro user=postgres');
  return $conn;
}




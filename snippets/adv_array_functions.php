<?php


//create an array of objects
$events = [ 
  0 => (object) [
    'type' => 'type3'
  ],
  1 => (object) [
    'type' => 'type2'
  ],
  2 => (object) [
    'type' => 'type3'
  ]
];

print_r("\nevents:");
print_r($events);


//get the type instance from each event
$typeinstances = array_map( function( $event ) { return $event->type; }, $events );
print_r("\ntypeinstances:");
print_r($typeinstances);


//translate types to scores
$scores = array(
    'type1' => 2,
    'type2' => 5,
    'type3' => 10,
);

print_r("\nscores:");
print_r($scores);

$score = array_reduce( $typeinstances, function( $result, $type ) use ( $scores ) { 
        return ((isset( $scores[$type]) ? $scores[$type] : 0 ) > $result) ? $scores[$type] : $result; } );


print_r("\nscore:");
print_r($score);

print_r("\n\n\n");







$people = array(
  array( "name" => "Fred", "age" => 39 ),
  array( "name" => "Sally", "age" => 23 ),
  array( "name" => "Marge", "age" => 62),
  array( "name" => "Benny", "age" => 6 ),
  array( "name" => "Bill", "age" => 70 ),
//  array( "name" => "Mark", "age" => 37 ),
  array( "name" => "Patrick", "age" => 26 )
);

function getSortFunction( $sortKey ) {
  print_r("\ngetSortFunction called"); 
  return function( $personA, $personB ) use ( $sortKey ) {
    print_r("\nanonFunction called");
    return ( $personA[$sortKey] < $personB[$sortKey] ) ? -1 : 1;
  };
}

echo "Sorted by name:<br><br>";
usort( $people, getSortFunction( "name" ) );
print_r( $people );
echo "<br>";

echo "Sorted by age:<br><br>";
usort( $people, getSortFunction( "age" ) );
print_r( $people );
echo "<br>";







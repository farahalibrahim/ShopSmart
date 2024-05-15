<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);

// Get the data from the AJAX request
$supermarketId = $_POST['supermarket_id'];
$newRating = $_POST['rating'];


// fetch the current rating and the number of ratings
$query = "SELECT rating, nb_of_ratings FROM supermarket WHERE id = :supermarket_id";

$stmt = DatabaseHelper::runQuery($conn, $query, ['supermarket_id' => $supermarketId]);


// Fetch the result
$result = $stmt->fetch();

// Calculate the new average rating
$oldRatingTotal = $result['rating'] * $result['nb_of_ratings'];
$newNbOfRatings = $result['nb_of_ratings'] + 1;
$newAverageRating = ($oldRatingTotal + $newRating) / $newNbOfRatings;

// Prepare the SQL query to update the rating and the number of ratings
$query = "UPDATE supermarket SET rating = :rating, nb_of_ratings = :nb_of_ratings WHERE id = :supermarket_id";

// Prepare the statement
$stmt = DatabaseHelper::runQuery($conn, $query, ['rating' => $newAverageRating, 'nb_of_ratings' => $newNbOfRatings, 'supermarket_id' => $supermarketId]);

// Return a response
echo 'Rating saved successfully.';

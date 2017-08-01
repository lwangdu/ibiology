<?php

	// display the global questiona and answers at a talk or session level.

$questions = get_field( 'assessment_questions' );
$answers = get_field( 'assessment_answers' );

if ( !empty( $questions ) ){
	echo "<h4>Questions</h4><div class='questions'>{$questions}</div>";
}


if ( !empty( $answers ) ){
	echo "<h4>Answers</h4><a class='toggle' data-toggle='session_answers'>View Answers</a><div id='session_answers'>{$answers}</div>";
}

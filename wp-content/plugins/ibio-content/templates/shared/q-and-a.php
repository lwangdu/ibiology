<?php
	
global $question_content;
var_dump($question_content);
if ( isset( $question_content['questions'] ) ){
	echo "<h4>Questions</h4><div class='questions'>{$question_content['questions']}</div>";
}


if ( isset( $question_content['answers'] ) ){
	echo "<h4>Answers</h4><div class='questions'>{$question_content['answers']}</div>";
}
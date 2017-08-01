<?php
	
global $question_content;
if ( isset( $question_content['questions'] ) ){
	echo "<h4>Discussion Questions</h4><div class='questions'>{$question_content['questions']}</div>";
}


if ( !empty( $question_content['answers'] ) ){
	echo "<h4>Answers</h4><div class='questions'>{$question_content['answers']}</div>";
}
<?php


class TestTask extends BuildTask {
	
	public function run($request) {
		
		$subject = "Test Email!";
		$body = "<p> ... </p>";
		
		$email = new Email("test@mkpl.co", "robster31@gmail.com", $subject, $body);
		$email->sendPlain();
		
		echo "Sent!";
	}
}

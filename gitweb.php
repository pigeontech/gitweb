#!/usr/local/php54/bin/php
<?php
	// Output greeting info
	greeting();

	// Make sure STDIN is set
	if (!defined("STDIN"))
	{
		define("STDIN", fopen('php://stdin','r'));
	}

	// Get username
	$username = rtrim(shell_exec('whoami'),"\n");

	// Get list of websites into array
	exec('for i in $(ls -d */); do echo ${i%%/}; done', $websites);

	// Ask which website, ask again if out of range
	$choice = 0;
	while ($choice < 1 || $choice > count($websites))
	{
		echo " Choose which website you want to manage with Git...\n\n";
		for ($i = 0; $i < count($websites); $i++)
		{
			echo '     #'.($i+1).' - '.$websites[$i]."\n";	
		}

		echo "\n Type the number here: ";
		$choice = (int)fread(STDIN, 80);

		if ($choice < 1 || $choice > count($websites))
		{
			echo "\n That choice was out of range. Try again.\n\n";
		}
	}
	
	$pr =
		"#!/bin/sh\n".
		"git --work-tree=/home/$username/".$websites[$choice-1]." --git-dir=/home/$username/.git/".$websites[$choice-1].".git checkout -f\n";

	shell_exec("git init .git/".$websites[$choice-1].".git --bare");
	shell_exec('echo "'.$pr.'" > .git/'.$websites[$choice-1].'.git/hooks/post-receive');
	shell_exec('chmod +x .git/'.$websites[$choice-1].'.git/hooks/post-receive');


	function greeting()
	{
		$greeting =
		"\n".
		" ----------------------------------- GitWeb -----------------------------------\n".
		" ------------------------------------------------------------------------------\n".
		" This program will help you create a git repository on your host, and then set\n".
		" it up so that when you push updates to it, your web root will get updated too.\n".
		" Obviously, only push updates you're okay with being on the LIVE website!\n".
       	" ------------------------------------------------------------------------------\n\n";

		echo $greeting;
	}


?>
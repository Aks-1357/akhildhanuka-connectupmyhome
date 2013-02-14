<?php

$help = $_GET['help'];

$url = "http://www.freestyle-joomla.com/comhelp/fss/" . $help;

header("Location: $url");
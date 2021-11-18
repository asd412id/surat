<?php

return [
	'mode'                  => 'utf-8',
	'format'                => [215, 330],
	'author'                => env('INS_NAME', 'UPTD SMPN 39 SINJAI'),
	'subject'               => 'Persuratan ' . env('INS_NAME', 'UPTD SMPN 39 SINJAI'),
	'keywords'              => 'Persuratan, PDF, asd412id, ' . env('INS_NAME', 'UPTD SMPN 39 SINJAI'),
	'creator'               => env('INS_NAME', 'UPTD SMPN 39 SINJAI'),
	'display_mode'          => 'fullpage',
	'tempDir'               => storage_path('temp'),
	'pdf_a'                 => false,
	'pdf_a_auto'            => false,
	'icc_profile_path'      => ''
];

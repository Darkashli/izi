<?php

// Turn off output buffering
ini_set('output_buffering', 'off');
// Turn off PHP output compression
ini_set('zlib.output_compression', false);
         
//Flush (send) the output buffer and turn off output buffering
//ob_end_flush();
while (@ob_end_flush());
         
// Implicitly flush the buffer(s)
ini_set('implicit_flush', true);
ob_implicit_flush(true);
 
//prevent apache from buffering it for deflate/gzip
header("Content-type: text/plain");
header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
 
for($i = 0; $i < 1000; $i++)
{
echo ' ';
}
         
ob_flush();
flush();
 
/// Now start the program output
 
echo "Program Output";

for($i = 0; $i < 10; $i++){
    //Hard work!!
    sleep(1);
    $p = ($i+1)*10; //Progress
    $response = array(  'message' => $p . '% complete. server time: ' . date("h:i:s", time()), 
                        'progress' => $p);
    
    echo json_encode($response);
    
}
 
ob_flush();
flush();
<?php
if (isset($_POST['img']))
{
    // Get the data
    $imageData=$_POST['img'];
 
    // Remove the headers (data:,) part.  
    // A real application should use them according to needs such as to check image type
    $filteredData=substr($imageData, strpos($imageData, ",")+1);
 
    // Need to decode before saving since the data we received is already base64 encoded
    $decodedData=base64_decode($filteredData);
 
    // Save file.  This example uses a hard coded filename for testing, 
    // but a real application can specify filename in POST variable
    $fp = fopen( '../collageImages/'.$_POST['id'].'.png', 'wb' );
    if($fp){
        fwrite( $fp, $decodedData);
        fclose( $fp );
        echo 'http://beta.clipr.in/collageImages/'.$_POST['id'].'.png';
    }
}
?>

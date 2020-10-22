<?php
    require ('../vendor/autoload.php');

    $barcode = new \Com\Tecnick\Barcode\Barcode();

    
    

    $cURLConnection = curl_init();
    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "content-type: application/json";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    $header[] = "Pragma: "; // browsers keep this blank. 

    curl_setopt($cURLConnection, CURLOPT_URL, 'https://api.trongrid.io/wallet/generateaddress');
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $header); 
    
    $phoneList = curl_exec($cURLConnection);
    curl_close($cURLConnection);
    $phonD1 = explode('","',$phoneList);
    $private = explode('":"', $phonD1[0]);
    $address = explode('":"', $phonD1[1]);
    $haddress = explode('":"', $phonD1[2]);
    echo " <br><br>Private Key => " . $private[1];
    echo " <br><br>Address => " . $address[1];
    echo " <br><br>Hex Address => " . $haddress[1];
    
    $bobj = $barcode->getBarcodeObj('QRCODE,H', $address[1], -4, -4, 'black', array(-2, -2, -2, -2))->setBackgroundColor('#f0f0f0');

    echo "
            <img alt=\"Embedded Image\" src=\"data:image/png;base64,".base64_encode($bobj->getPngData())."\" />
    ";

?>
<script src="/assets/js/jquery.min.js"></script>

<span id="result"></span>
<script>
    
    $(document).ready(function(){
        // Send the form data using post
        $.ajax({
            url: "https://api.trongrid.io/wallet/validateaddress", 
            data: 'address=<?php echo $address[1]; ?>',
            // Display the returned data in browser
            success: function(data){
                alert(data);
            }
            
        });
        $.ajax({
            url: "https://api.trongrid.io/wallet/generateaddress", 
            // Display the returned data in browser
            success: function(data){
                document.getElementById("result").innerHTML = data.toString();
                alert(data);
            }
            
        });
    });


</script>
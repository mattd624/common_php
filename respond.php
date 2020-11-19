<?php
//function to respond to salesforce using the soap formatted message below 
//input: 'true' or 'false'
function respond($tf) {
print '<?xml version="1.0" encoding="UTF-8"?>
  <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
       <soapenv:Body>
           <notifications xmlns="http://soap.sforce.com/2005/09/outbound">
               <Ack>' .$tf.'</Ack>
              </notifications>
          </soapenv:Body>
      </soapenv:Envelope>';
}

function respond_early() {
  
                                                                                      heavylog("\nCLEARING OUTPUT BUFFER AND RESPONDING \"TRUE\" TO SF");
  if (!empty(ob_get_contents())) ob_clean();
  respond('true');
  header('Connection: close');
  header('Content-Length: '.ob_get_length());
  if (!empty(ob_get_contents())) ob_end_flush();
  if (!empty(ob_get_contents())) ob_flush();
  flush();
}

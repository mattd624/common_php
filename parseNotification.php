<?php
function parseNotification($domDoc){
    // Parse Salesforce Outbound Message Notification parameters into result array

    $result = array("OrganizationId" => "","ActionId" => "","SessionId" => "","EnterpriseUrl" => "","PartnerUrl" => "","sObject" => null,"MapsRecords" => array());

    $result["OrganizationId"] = $domDoc->getElementsByTagName("OrganizationId")->item(0)->textContent;
    $result["ActionId"] = $domDoc->getElementsByTagName("ActionId")->item(0)->textContent;
    $result["SessionId"] = $domDoc->getElementsByTagName("SessionId")->item(0)->textContent;
    $result["EnterpriseUrl"] = $domDoc->getElementsByTagName("EnterpriseUrl")->item(0)->textContent;
    $result["PartnerUrl"] = $domDoc->getElementsByTagName("PartnerUrl")->item(0)->textContent;

    // Create sObject and fill fields provided in notification
    $sObjectNode = $domDoc->getElementsByTagName("sObject")->item(0);
    /*$sObjType = $sObjectNode->getAttribute("type");
    if (substr_count($sObjType,"sf:"))
    {
        $sObjType = substr($sObjType,3);
    }
    //$result["sObject"] = new SObject($sObjType);
    //$result["sObject"]->type = $sObjType;
    */
    $sObjectNodes = $domDoc->getElementsByTagNameNS('urn:sobject.enterprise.soap.sforce.com','*');
    //$result["sObject"]->fieldnames = array();
    $count = 0;
    $tempMapRecord = array();
    foreach ($sObjectNodes as $node)
    {
        if ($node->localName == "Id")
        {
            if ($count > 0)
            {
                $result["MapsRecords"][] = $tempMapRecord;
                $tempMapRecord = array();
            }
            $tempMapRecord[$node->localName] = $node->textContent;
        }
        else
        {
          $tempMapRecord[$node->localName] = $node->textContent;
        }
        $count++;
    }

    // Finish last item
    $result["MapsRecords"][] = $tempMapRecord;

    return $result;
}



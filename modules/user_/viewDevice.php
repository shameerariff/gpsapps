<?php
$getDevice = "SELECT * FROM tb_deviceinfo WHERE di_userId =".$_SESSION[userID]." ORDER BY di_deviceId DESC";

$rowDevice = $db->query($getDevice);
$addCount = $db->affected_rows;

?>
<script language="javascript">
function funEditUser(uid,cid,act)
{
	document.frmSubmit.txtDeviceId.value = uid;
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
</script>


<div class="pagearea"><!-- Pagearea div start here -->
<!--<div align="left" class="listofusers">List of Device<?php if($recordUserInfo[ci_clientType] == "Reseller") { ?> / <a href="#" onclick="funEditUser('','<?php echo $_SESSION[clientID];?>','?ch=addClientDevice')">Add Device</a><?php } ?></div>-->
<div align="center">
<table class="gridform_final" >
    <tr>
    <td>
   <?php
    //$sql = "SELECT * FROM tb_deviceinfo WHERE di_userId =".$_SESSION[userID]." AND di_clientId = ".$_SESSION[clientID]." ORDER BY di_deviceId DESC";
	$sql = "SELECT * FROM tb_deviceinfo,tb_client_subscription,tb_clientinfo WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId=ci_id AND ci_id=".$_SESSION[clientID]." order by di_deviceName,di_deviceId,ci_clientName ASC";
	$rows = $db->query($sql);
	$i = 0;
   if($db->affected_rows > 0)
   {
   	while ($record = $db->fetch_array($rows)) 
	{
		if($record[di_deviceName])
			$devName = $record[di_deviceName];
		else
			$devName = $record[di_deviceId];
		
		$renewDate = date("d-m-Y",strtotime("-1 days ".($record[tcs_noOfMonths]) ."months ".$record[tcs_renewalDateFrom]));
		if(strtotime(date("d-m-Y")) <= strtotime($renewDate))
		{
			$expStatus = "<span>Monthly subscription will expire on ".$renewDate."</span>";
		}
		else
		{
			$expStatus = "<span style='color:red'>Monthly subscription expired </span>";
		}
		
		$getInsurance = "select * from tb_device_insurance_info where tdii_deviceId = ".$record[di_id];
		$resInsurance = $db->query($getInsurance);
		if($db->affected_rows >0)
		{
			$fetInsurance = $db->fetch_array($resInsurance);
			$insExpDate ="<span>Insurance will expire on ". $fetInsurance[tdii_policyExpDate]."</span>";
		}
		else
		{
			$insExpDate ="Nil";
		}
		
	?>
    
		<table width="100%"class="uniform_final">
          <tr>
            <td width="15%"><table width="100%" border="0">
              <tr>
                <td><img src="unit_img/<?php echo $record[di_deviceImg];?>" width="75" height="75" title="<?php echo ucfirst($devName);?>" alt="<?php echo ucfirst($devName);?>" /></td>
              </tr>
              <tr>
                <th><?php echo ucfirst($devName);?></th>
              </tr>
            </table></td>
            <td valign="top"><table width="100%">
              <tr>
                <td align="left" style="border:0px;"><table width="100%">
                  <tr>
                    <td style="border:0px;"><span class="form_text">IMEI :</span> &nbsp; <?php echo $record[di_imeiId];?></td>
                    <td style="border:0px;"><span class="form_text">Model :</span> &nbsp; <?php echo $record[di_deviceModel];?></td>
                    <td style="border:0px;"><span class="form_text">Mobile :</span> &nbsp; <?php echo $record[di_mobileNo];?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td style="border-bottom:0px; border-left:0px; border-right:0px;"><table width="100%">
                  <tr>
                    <td width="80%" align="left" style="border:0px;"><span class="form_text">Subs .Status : </span> &nbsp; <?php echo $expStatus;?></td>
                    <td align="left" width="10%" style="border:0px;"><a class="error_strings" href="#" onclick="funEditUser('<?php echo $record[di_id];?>','<?php echo $_SESSION[clientID];?>','?ch=addClientDevice')">Edit Device</a></span></td>
                    <td align="left" width="10%" style="border:0px;"><a class="error_strings" href="#" onclick="funEditUser('','<?php echo $_SESSION[clientID];?>','?ch=insurance')">Edit Insurance</a></span></td>
                  </tr>
                  <tr>
                    <td width="80%" colspan="3" align="left" style="border:0px;"><span class="form_text">Insurance Status : </span> &nbsp; <?php echo $insExpDate;?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table>    
    	<br />

    <?php
	$i++;
	}
	}
	else
	{
   ?>
        <td><span>Not data found</span></td>
   <?php
	}
   ?>
   </td>
   </tr>
</table>
</div>
</div>
</div>
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtDeviceId" id="txtDeviceId" />
    <input type="hidden" name="txtClientId" id="txtClientId" />
</form>
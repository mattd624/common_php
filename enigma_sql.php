<?php
//require_once realpath(__DIR__ . '/../commonDirLocation.php');
//require_once realpath(COMMON_PHP_DIR . '/creds.php');
////// OR //////
require_once 'creds.php';


function enigma_get_avg_env_mon_values($hst_name = null, $mon_name = null, $last_x_days = 1) {
/*
hst_name = an enigma host name
mon_name = monitor name as found in the upsmib_dsc
last_x_days = number of days to look back from the present time
*/
  $m = new mysqli("enig-01-dmz.unwiredbb.net", ENIG_USER, ENIG_PW, "nms");
  if ($m->connect_errno) {
      echo "Failed to connect to MySQL: (" . $m->connect_errno . ") " . $m->connect_error;
  }
  
  $q = "select
           u.upsmib_id AS 'enig_upsmib_id'
          ,u.upsmib_dsc AS 'enig_upsmib_dsc'
          ,m.mib_col_id AS 'enig_mib_col_id' 
          ,h.hst_namea AS 'enig_hst_name'
          ,h.hst_connection_comment AS 'sf_id'
  FROM upsmib u
  JOIN mib_col m
      ON u.upsmib_id = m.mib_col_upsmib_id
  JOIN hst h
      ON m.mib_col_hst_id = h.hst_id
  where 1
      AND upsmib_dsc_aux LIKE '%".$mon_name."%'
      AND hst_namea LIKE '%".$hst_name."%'";
//print_r($q);

  $res = $m->query($q)->fetch_all($resulttype = MYSQLI_ASSOC);
//print_r($res);
  $dev_arr = [];
  foreach ($res as $r) {
    //$int_arr = $r;
//print_r($int_arr['mib_col_id']);
    $q = "SELECT avg(mib_val_log_val) \"avg_mib_val\" from mib_val_log_" . $r['enig_mib_col_id'] .
         " WHERE FROM_UNIXTIME(mib_val_log_tst) > DATE_SUB(curdate(), INTERVAL ".$last_x_days." DAY)";
    $res = $m->query($q)->fetch_object();
//print_r($res);
    //$int_arr['avg_mib_val'] = $res->avg_mib_val;
    $r['avg_mib_val'] = $res->avg_mib_val;
    $dev_arr[$r['sf_id']][str_replace(' ','_',$mon_name)] = $r;
  }
  return $dev_arr;
}


/*
$avg_tx_7day = enigma_get_avg_env_mon_values('aosn-ap-', 'tx rate', 7);
print_r("Avg Tx - 7 day: \n");
print_r($avg_tx_7day); 
$avg_rx_7day = enigma_get_avg_env_mon_values('aosn-ap-', 'Rx Rate', 7);
print_r("Avg Rx - 7 day: \n");
print_r($avg_rx_7day); 
*/



function enigma_get_daily_peak_util_avg($hst_name = null, $int_param, $last_x_days = 1) {
/*
hst_name = an enigma host name
int_param = interface parameter as found in the mrtg_cfg_title
last_x_days = number of days to look back from the present time

*/
  $m = new mysqli("enig-01-dmz.unwiredbb.net", ENIG_USER, ENIG_PW, "nms");
  if ($m->connect_errno) {
      echo "Failed to connect to MySQL: (" . $m->connect_errno . ") " . $m->connect_error;
  }
  $q = "select
           c.mrtg_cfg_id AS 'enig_mrtg_cfg_id'
          ,c.mrtg_cfg_interface AS 'enig_mrtg_cfg_interface'
          -- ,c.mrtg_cfg_int_ind AS 'enig_mrtg_cfg_int_ind'
          -- ,c.mrtg_cfg_title AS 'enig_mrtg_cfg_title'
	  ,h.hst_namea AS 'enig_hst_name'
	  ,h.hst_id AS 'enig_hst_id'
	  ,h.hst_ip AS 'enig_hst_ip'
          ,h.hst_connection_comment AS 'sf_id'
  FROM mrtg_cfg c
  JOIN hst h
      ON c.mrtg_cfg_hst_id = h.hst_id
  where 1
      AND hst_namea LIKE '%".$hst_name."%'
      AND c.mrtg_cfg_int_ind_label = 'eth0'
      AND c.mrtg_cfg_title LIKE '%" . $int_param . "%'
  ";

  $res = $m->query($q)->fetch_all($resulttype = MYSQLI_ASSOC);
//  print_r($res);
  $dev_arr = [];
  foreach ($res as $r) {
    $dev_arr[$r['sf_id']] = $r;
    $int_arr = [];
    $cfg_id = $r['enig_mrtg_cfg_id'];
    for ($i=0;$i<$last_x_days;$i++) {
      $i2 = $i + 1;
      $q = "SET @row = 0;
            SELECT *
            FROM ( 
                SELECT 
                @row:=@row + 1 'row' 
                , FROM_UNIXTIME(mrtg_util_tst) 'timestamp' 
                , mrtg_util_in_val_abs*8 AS 'enig_port_in_val' 
          	, mrtg_util_out_val_abs*8 AS 'enig_port_out_val' 
          	, total.nth 
          	, total.total 
      	FROM mrtg_util_$cfg_id
      	CROSS JOIN ( 
          	SELECT round(count(*) * 0.99) 'nth', count(*) 'total' 
          	FROM mrtg_util_$cfg_id
          	WHERE   FROM_UNIXTIME(mrtg_util_tst) < DATE_SUB(curdate(), INTERVAL $i day) 
              		AND FROM_UNIXTIME(mrtg_util_tst) > DATE_SUB(curdate(), INTERVAL $i2 day) 
      	) total 
      	WHERE 1 
          	AND FROM_UNIXTIME(mrtg_util_tst) < DATE_SUB(curdate(), INTERVAL $i day) 
          	AND FROM_UNIXTIME(mrtg_util_tst) > DATE_SUB(curdate(), INTERVAL $i2 day) 
      	ORDER BY mrtg_util_in_val_abs*8 
  	) u 
  	WHERE u.row = u.nth 
  	;";
      
//print_r($q);

      if ($m->multi_query($q)) {
          do {
              // store first result set
              if ($res = $m->store_result()) {
                  while ($arr = $res->fetch_array(MYSQLI_ASSOC)) {
                      //print_r($arr);
                      $int_arr['daily'][$i2] = $arr;
                  }
                  $res->free();
              }
              // print divider
      //        if ($m->more_results()) {
      //            printf("-----------------\n");
      //        }
      
          } while ($m->more_results() and $m->next_result());
      }


      $dev_arr[$r['sf_id']][$r['enig_mrtg_cfg_interface']] = $int_arr;
    }
  }
  return $dev_arr;
}

/*
$test = enigma_get_daily_peak_util_avg('aosn-ap-', 'utilisation bps', 7);

print_r("test: \n");
print_r($test);
*/

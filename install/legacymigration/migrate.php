<?php
  try {
      $old_tbl_prefix = $_POST['tblprefix'];
      $new_tbl_prefix = $_POST['newtblprefix'];

      $oldconn = new \PDO('mysql:host='.$_POST['dbhost'].';dbname='.$_POST['dbname'].';charset=utf8', $_POST['dbuser'], $_POST['dbpw']);

      $oldmembersql = "SELECT m.id, m.username, m.password, m.email, m.verified, a.adminid,
                          a.active, a.superadmin, mi.userid, mi.firstname, mi.lastname, mi.phone, mi.address1,
                          mi.address2, mi.city, mi.state, mi.country, mi.bio, mi.userimage
                  FROM {$old_tbl_prefix}members m
                  LEFT JOIN {$old_tbl_prefix}admins a on a.userid = m.id
                  LEFT JOIN {$old_tbl_prefix}member_info mi on mi.userid = m.id";

      $oldquery = $oldconn->prepare($oldmembersql);
      $oldquery->execute();
      $old_members = $oldquery->fetchAll(\PDO::FETCH_ASSOC);

      $members_cols = ['id', 'username', 'password', 'email', 'verified'];
      $admins_cols = ['admin_id', 'active', 'superadmin'];
      $member_info_cols = ['userid', 'firstname', 'lastname', 'phone', 'address1', 'address2', 'city', 'state', 'country', 'bio', 'userimage'];

      $new_member_array = [];

      $newconn = new \PDO('mysql:host='.$_POST['newdbhost'].';dbname='.$_POST['newdbname'].';charset=utf8', $_POST['newdbuser'], $_POST['newdbpw']);

      ob_start();

      foreach ($old_members as $old_mem) {
          $member_insert_string = "INSERT IGNORE INTO {$new_tbl_prefix}members (id, username, password, email, verified) values ('{{{id}}}', '{{{username}}}', '{{{password}}}', '{{{email}}}', '{{{verified}}}');";
          $member_role_insert_string = "INSERT IGNORE INTO {$new_tbl_prefix}member_roles (member_id, role_id) values ('{{{member_id}}}', '{{{role_id}}}');";
          $member_info_insert_string = "INSERT IGNORE INTO {$new_tbl_prefix}member_info (userid, firstname, lastname, phone, address1, address2, city, state, country, bio, userimage) values ('{{{userid}}}', '{{{firstname}}}', '{{{lastname}}}', '{{{phone}}}', '{{{address1}}}', '{{{address2}}}', '{{{city}}}', '{{{state}}}', '{{{country}}}', '{{{bio}}}', '{{{userimage}}}');";

          $member_info_insert_string_blank = "INSERT IGNORE INTO {$new_tbl_prefix}member_info (userid, firstname, lastname, phone, address1, address2, city, state, country, bio, userimage) values ('', '', '', '', '', '', '', '', '', '', '');";

          $member_id = $old_mem['id'];

          foreach ($old_mem as $key => $val) {
              if (in_array($key, $members_cols)) {
                  $member_insert_string = str_replace("{{{{$key}}}}", $val, $member_insert_string);
              } elseif (in_array($key, $admins_cols)) {
                  $new_member_inserts['member_roles'][$key] = $val;
                  if ($key == 'superadmin' && $val == 1) {
                      $member_insert_string = str_replace("{{{role_id}}}", 1, $member_insert_string);
                      $member_insert_string = str_replace("{{{member_id}}}", $member_id, $member_insert_string);
                  } else {
                      $member_role_insert_string = str_replace("{{{role_id}}}", 3, $member_role_insert_string);
                      $member_role_insert_string = str_replace("{{{member_id}}}", $member_id, $member_role_insert_string);
                  }
              } elseif (in_array($key, $member_info_cols)) {
                  $member_info_insert_string = str_replace("{{{{$key}}}}", addslashes($val), $member_info_insert_string);
              }
          }

          echo $member_insert_string;
          if ($member_info_insert_string !== $member_info_insert_string_blank) {
              echo $member_info_insert_string;
          }
      }

      $mass_insert = ob_get_clean();

      $newquery = $newconn->prepare($mass_insert);
      $newquery->execute();

      $resparr = $newquery->errorInfo();

      if ($resparr[0] == 00000) {
          echo "Success!";
      } else {
          http_response_code(500);
          throw new Exception($resparr[2]);
      }
  } catch (\Exception $ex) {
      echo $ex->getMessage();
  } catch (\PDOException $e) {
      echo $e->getMessage();
  }

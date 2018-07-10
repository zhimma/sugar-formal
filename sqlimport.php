<?php

define('EXEC_MESSAGE', false); // done
define('EXEC_FAV', false); // done
define('EXEC_PIC', false); // done
define('EXEC_USER', true); // done
define('EXEC_VISITED', false);  // done

$host = "localhost";
$username = "webhelper_sugar";
$password = "sugar$$$";
$db = "webhelper_sugar";

$file = $argv[1];
if (isset($file)) {
    $handle = file_get_contents($file);
    
    if ($handle) {
        $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_PERSISTENT => true));
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute( PDO::ATTR_EMULATE_PREPARES, false);
        $lines = explode("\n", $handle);
        foreach ($lines as $line) {
            try {
                $line = trim($line);
                if (strpos($line, 'INSERT') == 0) { // marker start of table
                   /*  if (strpos($line, 'member_message')) {
                        $table = "message";
                        if (EXEC_MESSAGE) {
                            
                            $stmt = $conn->prepare("INSERT INTO `message` ( `created_at`, `updated_at`, `to_id`, `content`, `read`, `from_id`) VALUES (:created, :updated, :toid, :content, :isread, :fromid);");
                             $stmt->bindParam(":created", $created, PDO::PARAM_STR);
                             $stmt->bindParam(":updated", $updated, PDO::PARAM_STR);
                             $stmt->bindParam(":toid", $toid, PDO::PARAM_INT);
                             $stmt->bindParam(":content", $content, PDO::PARAM_STR);
                             $stmt->bindParam(":isread", $isread, PDO::PARAM_STR);
                             $stmt->bindParam(":fromid", $fromid, PDO::PARAM_INT);
                        }
                    }*//*  else  if (strpos($line, 'member_fav')) {
                        $table = "member_fav";
                        if (EXEC_FAV) {
                            $stmt = $conn->prepare("INSERT INTO `member_fav` (`member_id`, `member_fav_id`, `created_at`, `updated_at`) VALUES(:member_id, :fav_id, :created, :updated)");
                             $stmt->bindParam(":member_id", $member_id, PDO::PARAM_INT);
                             $stmt->bindParam(":fav_id", $fav_id, PDO::PARAM_INT);
                             $stmt->bindParam(":created", $created, PDO::PARAM_STR);
                             $stmt->bindParam(":updated", $updated, PDO::PARAM_STR);
                        }
                    } elseif (strpos($line, 'member_click')) {
                        $table = "visited";
                        if (EXEC_VISITED) {
                            $stmt = $conn->prepare('INSERT INTO `visited` ( `member_id`, `visited_id`) VALUES(:member_id, :visited_id)');
                             $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
                             $stmt->bindParam(':visited_id', $visited_id, PDO::PARAM_INT);
                        }
                    } *//*  else if (strpos($line, 'member_picture')) {
                        $table = "member_pic";
                        if (EXEC_PIC) {
                            $stmt = $conn->prepare('INSERT INTO `member_pic` (`member_id`, `pic`) VALUES(:member_id, :pic)');
                             $stmt->bindParam(':member_id', $member_id, PDO::PARAM_INT);
                             $stmt->bindParam(':pic', $pic, PDO::PARAM_STR);
                        }
                    }else */if (strpos($line, 'member')) {
                        $table = "users";
                        if (EXEC_USER) {
                            /*  $stmt = $conn->prepare('INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`, `title`, `engroup`, `enstatus`) VALUES (:id, :name, :email, :pwd, :created, :updated, :title, :engroup, :enstatus)');
                              $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                              $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                              $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                              $stmt->bindParam(':pwd', $pwd, PDO::PARAM_STR);
                              $stmt->bindParam(':created', $created, PDO::PARAM_STR);
                              $stmt->bindParam(':updated', $updated, PDO::PARAM_STR);
                              $stmt->bindParam(':title', $title, PDO::PARAM_STR);
                              $stmt->bindParam(':engroup', $engroup, PDO::PARAM_INT);
                              $stmt->bindParam(':enstatus', $enstatus, PDO::PARAM_INT);*/
                        
                              $stmt2 = $conn->prepare("INSERT INTO `user_meta` (`user_id`, `is_active`, `created_at`, `updated_at`, `city`, `area`, `budget`, `birthdate`, `height`, `weight`, `cup`, `body`, `about`, `style`, `situation`, `occupation`, `education`, `marriage`, `drinking`, `smoking`, `isHideArea`, `isHideCup`, `isHideWeight`, `isHideOccupation`, `country`, `memo`, `pic`, `domainType`, `domain`, `job`, `realName`, `assets`, `income`, `marketing`, `terms_and_cond`) VALUES (:user_id, :is_active, :createdm, :updatedm, :city, :area, :budget, :birthdate, :height, :weight, :cup, :body, :about, :style, :situation, :occupation, :education, :marriage, :drinking, :smoking, :isHideArea, :isHideCup, :isHideWeight, :isHideOccupation, :country, :memo, :pic, :domainType, :domain, :job, :realName, :assets, :income, :marketing, :terms)");
                              $stmt2->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                              $stmt2->bindParam(':is_active', $is_active, PDO::PARAM_INT);
                              $stmt2->bindParam(':createdm', $createdm, PDO::PARAM_STR);
                              $stmt2->bindParam(':updatedm', $updatedm, PDO::PARAM_STR);
                              $stmt2->bindParam(':area', $area, PDO::PARAM_STR);
                              $stmt2->bindParam(':budget', $budget, PDO::PARAM_STR);
                               $stmt2->bindParam(':city', $city, PDO::PARAM_STR);
                               $stmt2->bindParam(':income', $income, PDO::PARAM_STR);
                              $stmt2->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
                              $stmt2->bindParam(':height', $height, PDO::PARAM_INT);
                              $stmt2->bindParam(':weight', $weight, PDO::PARAM_INT);
                              $stmt2->bindParam(':cup', $cup, PDO::PARAM_STR);
                              $stmt2->bindParam(':body', $body, PDO::PARAM_STR);
                              $stmt2->bindParam(':about', $about, PDO::PARAM_STR);
                              $stmt2->bindParam(':style', $style, PDO::PARAM_STR);
                              $stmt2->bindParam(':situation', $situation, PDO::PARAM_STR);
                              $stmt2->bindParam(':occupation', $occupation, PDO::PARAM_STR);
                        
                              $stmt2->bindParam(':education', $education, PDO::PARAM_STR);
                              $stmt2->bindParam(':marriage', $marriage, PDO::PARAM_STR);
                              $stmt2->bindParam(':drinking', $drinking, PDO::PARAM_STR);
                              $stmt2->bindParam(':smoking', $smoking, PDO::PARAM_STR);
                              $stmt2->bindParam(':isHideArea', $isHideArea, PDO::PARAM_STR);
                              $stmt2->bindParam(':isHideCup', $isHideCup, PDO::PARAM_STR);
                              $stmt2->bindParam(':isHideWeight', $isHideWeight, PDO::PARAM_STR);
                        
                              $stmt2->bindParam(':isHideOccupation', $isHideOccupation, PDO::PARAM_STR);
                              $stmt2->bindParam(':country', $country, PDO::PARAM_STR);
                              $stmt2->bindParam(':memo', $memo, PDO::PARAM_STR);
                              $stmt2->bindParam(':pic', $pic, PDO::PARAM_STR);
                              $stmt2->bindParam(':domainType', $domainType, PDO::PARAM_STR);
                              $stmt2->bindParam(':domain', $domain, PDO::PARAM_STR);
                              $stmt2->bindParam(':job', $job, PDO::PARAM_STR);
                        
                              $stmt2->bindParam(':realName', $realName, PDO::PARAM_STR);
                              $stmt2->bindParam(':assets', $assets, PDO::PARAM_STR);
                              $stmt2->bindParam(':income', $income, PDO::PARAM_STR);
                              $stmt2->bindParam(':marketing', $marketing, PDO::PARAM_INT);
                              $stmt2->bindParam(':terms', $terms, PDO::PARAM_INT);
                        }
                    }
                    else { $table = ""; }
                } elseif (strpos($line, '(') == 0) {
                    /*if ($table == "message") {
                        $val = explode(', ', substr($line, 1, -3));
                        if (EXEC_MESSAGE) {
                            $created = trim($val[8]);
                            if (strpos($created, 'NULL') == false) {
                                $created = substr($created, 1, -1);
                            }
                            else { 
                                $created = (new \DateTime())->format('Y-m-d H:i:s');
                            }
                            $updated = $created;
                            $toid = $val[2];
                            $content = trim($val[5]);
                            if (strpos($content, 'NULL') == false) {
                                $content = substr($content, 1, -1);
                            }
                            $isread = 'Y';
                            $fromid = $val[1];
                            $stmt->execute();
                        }
                    } else*/if ($table == "users") {
                        $val = explode(', ', substr($line, 1, -3));
                        if (EXEC_USER) {
                            $id = $val[0];
                        /*    $name = trim($val[8]);
                            if (strpos($name, 'NULL') == false) {
                                $name = substr($name, 1, -1);
                            }
                            $title = trim($val[9]);
                            if (strpos($title, 'NULL') == false) {
                                $title = substr($title, 1, -1);
                            }
                            $email = trim($val[1]);
                            if (strpos($email, 'NULL') == false) {
                                $email = substr($email, 1, -1);
                            }
                            $pwd = trim($val[3]);
                            if (strpos($pwd, 'NULL') == false) {
                                $pwd = substr($pwd, 1, -1);
                            }
                            else $pwd = password_hash("test123");
                            if (strpos(trim($val[6]), 'NULL') !== false) {
                                $engroup = 0;
                            }
                            else $engroup = $val[6];
                            $enstatus = $val[5];
                            if (strpos($enstatus, 'NULL') !== false) {
                                $enstatus = 0;
                            }
                            $created = trim($val[46]);
                            if (strpos($created, 'NULL') == false) {
                                $created = substr($created, 1, -1);
                            }
                            else { 
                                $created = (new \DateTime())->format('Y-m-d H:i:s');
                            }
                            */
                            $updated = trim($val[48]);
                            if (strpos($updated, 'NULL') == false) {
                                $updated = substr($updated, 1, -1);
                            }
                            else { 
                                $updated = $created;
                            }
                          //  $stmt->execute();
                        
                            $marketing = 0;
                            $terms = 1;
                            $user_id = $id;
                            $is_active = 1;
                            $createdm = $created;
                            $updatedm = $updated;
                            
                            $realName = trim($val[7]);
                            if (strpos($realName, 'NULL') == false) {
                                $realName = substr($realName, 1, -1);
                            }
                        
                            $assets = trim($val[24]);
                            if (strpos($assets, 'NULL') == false) {
                                $assets = substr($assets, 1, -1);
                            }
                            $income = trim($val[25]);
                            if (strpos($income, 'NULL') == false) {
                                $income = substr($income, 1, -1);
                            }
                            $city = trim($val[20]);
                            if (strpos($city, 'NULL') == false) {
                                $city = substr($city, 1, -1);
                            }
                            $area = trim($val[21]);
                            if (strpos($area, 'NULL') == false) {
                                $area = substr($area, 1, -1);
                            }
                            $budget = trim($val[26]);
                            if (strpos($budget, 'NULL') == false) {
                                $budget = substr($budget, 1, -1);
                            }
                            $birthdate = trim($val[14]);
                            if (strpos($birthdate, 'NULL') == false) {
                                $birthdate = substr($birthdate, 1, -1);
                            }
                            $height = trim($val[15]);
                            if (strpos($height, 'NULL') == false) {
                                $height = (int) substr($height, 1, -1);
                            }
                            else $height = 0;
                            $weight = trim($val[16]);
                            if (strpos($weight, 'NULL') == false) {
                                $weight = (int) substr($weight, 1, -1);
                            }
                            else $weight = 0;
                            $cup = trim($val[17]);
                            if (strpos($cup, 'NULL') == false) {
                                $cup = substr($cup, 1, -1);
                            }
                            $cup = substr($cup, 0, 1);
                            $body = trim($val[27]);
                            if (strpos($body, 'NULL') == false) {
                                $body = substr($body, 1, -1);
                            }
                            $about = trim($val[36]);
                            if (strpos($about, 'NULL') == false) {
                                $about = substr($about, 1, -1);
                            }
                            $style = trim($val[25]);
                            if (strpos($style, 'NULL') == false) {
                                $style = substr($style, 1, -1);
                            }
                            $situation = "";
                            $occupation = trim($val[29]);
                            if (strpos($occupation, 'NULL') == false) {
                                $occupation = substr($occupation, 1, -1);
                            }
                            $education = trim($val[30]);
                            if (strpos($education, 'NULL') == false) {
                                $education = substr($education, 1, -1);
                            }
                            $marriage = trim($val[33]);
                            if (strpos($marriage, 'NULL') == false) {
                                $marriage = substr($marriage, 1, -1);
                            }
                            $drinking = trim($val[35]);
                            if (strpos($drinking, 'NULL') == false) {
                                $drinking = substr($drinking, 1, -1);
                            }
                            $smoking = trim($val[34]);
                            if (strpos($smoking, 'NULL') == false) {
                                $smoking = substr($smoking, 1, -1);
                            }
                            $isHideArea = trim($val[41]);
                            if (strpos($isHideArea, 'NULL') == false) {
                                $isHideArea = substr($isHideArea, 1, -1);
                            }
                            $isHideArea = substr($isHideArea, 0, 1);
                            $isHideCup = trim($val[39]);
                            if (strpos($isHideCup, 'NULL') == false) {
                                $isHideCup = substr($isHideCup, 1, -1);
                            }
                            $isHideCup = substr($isHideCup, 0, 1);
                            $isHideWeight = trim($val[38]);
                            if (strpos($isHideWeight, 'NULL') == false) {
                                $isHideWeight = substr($isHideWeight, 1, -1);
                            }
                            $isHideWeight = substr($isHideWeight, 0, 1);
                            $isHideOccupation = trim($val[40]);
                            if (strpos($isHideOccupation, 'NULL') == false) {
                                $isHideOccupation = substr($isHideOccupation, 1, -1);
                            }
                            $isHideOccupation = substr($isHideOccupation, 0, 1);
                            $country = trim($val[19]);
                            if (strpos($country, 'NULL') == false) {
                                $country = substr($country, 1, -1);
                            }
                            $memo = trim($val[37]);
                            if (strpos($memo, 'NULL') == false) {
                                $memo = substr($memo, 1, -1);
                            }
                            $pic = trim($val[10]);
                            if (strpos($pic, 'NULL') == false) {
                                $pic = substr($pic, 1, -1);
                            }
                            $domainType = trim($val[31]);
                            if (strpos($domainType, 'NULL') == false) {
                                $domainType = substr($domainType, 1, -1);
                            }
                            $domain = trim($val[32]);
                            if (strpos($domain, 'NULL') == false) {
                                $domain = substr($domain, 1, -1);
                            }
                            $job = trim($val[28]);
                            if (strpos($job, 'NULL') == false) {
                                $job = substr($job, 1, -1);
                            }
                            $stmt2->execute();
                        }
                    }/* else  if ($table == "member_fav") {
                        $val = explode(', ', substr($line, 1, -3));
                        if (EXEC_FAV) {
                            $member_id = $val[1];
                            $fav_id = $val[2];
                            $created = trim($val[4]);
                            if (strpos($created, 'NULL') == false) {
                                $created = substr($created, 1, -1);
                            }
                            else $created = (new \DateTime())->format('Y-m-d H:i:s');
                            $updated = $created;
                            $stmt->execute();
                        }
                    }  elseif ($table == "visited") {
                        $val = explode(', ', substr($line, 1, -3));
                        if (EXEC_VISITED) {
                            $visited_id = $val[2];
                            $member_id = $val[1];
                            $stmt->execute();
                        }
                    }*//* else if ($table == "member_pic") {
                        $val = explode(', ', substr($line, 1, -3));
                        if (EXEC_PIC) {
                            $pic = trim($val[2]);
                            if (strpos($pic, 'NULL') == false) {
                                $pic = substr($pic, 1, -1);
                            }
                            $member_id = $val[1];
                            $stmt->execute();
                        }
                    }*/
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage().PHP_EOL.PHP_EOL;
            }
        }
        $stmt = $conn->prepare('ALTER TABLE users AUTO_INCREMENT = 7000');
        $stmt->execute();
        $stmt = $conn->prepare('ALTER TABLE message AUTO_INCREMENT = 140000');
        $stmt->execute();
        $stmt = $conn->prepare('ALTER TABLE visited AUTO_INCREMENT = 140000');
        $stmt->execute();
           $stmt = $conn->prepare('ALTER TABLE member_fav AUTO_INCREMENT = 6000');
        $stmt->execute();
        $stmt = $conn->prepare('ALTER TABLE member_pic AUTO_INCREMENT = 1600');
        $stmt->execute();
        
        fclose($handle);
        $conn = null;
    } else {
        echo 'Error opening file'.PHP_EOL;
    }
}

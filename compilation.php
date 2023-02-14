<?php
    //Please excuse the messy code, had no time to turn things into functions

    declare (strict_types=1);

    //Check for username and password
    if (isset($_POST["user"]) && isset($_POST["password"])) {

        //Sanitize dates and fill them with default values if not provided.
        if (isset($_POST["from"])) {
            try {
                $from = new DateTime(filter_var($_POST["from"],FILTER_SANITIZE_SPECIAL_CHARS));

                $from = $from->format('Y-m-d H:i:s');
            }

            catch (Exception $e) {
                if (isset($_POST["testing"])) {
                    $msg = new stdClass();
                    $msg->response = 0;
                    $msg->message = "Invalid from input.";
                    header("Content-Type:application/json; charset=UTF-8");
                    echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    exit();

                } else {
                    header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                    exit();
                }
            }
        } else {
            $from = (new DateTime())->setTimestamp(0);
            $from = date_format($from,"Y-m-d H:i:s");
        }

        if (isset($_POST["to"])) {
            try {
                $to = new DateTime(filter_var($_POST["to"],FILTER_SANITIZE_SPECIAL_CHARS));

                $to = $to->format('Y-m-d H:i:s');
            }

            catch (Exception $e) {
                if (isset($_POST["testing"])) {
                    $msg = new stdClass();
                    $msg->response = 0;
                    $msg->message = "Invalid to input.";
                    header("Content-Type:application/json; charset=UTF-8");
                    echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    exit();

                } else {
                    header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                    exit();
                }
            }
        } else {
            $to = new DateTime("2970-01-01 00:00:00");
            $to = date_format($to,"Y-m-d H:i:s");
        }

        if ($to < $from) {
            if (isset($_POST["testing"])) {
                $msg = new stdClass();
                $msg->response = 0;
                $msg->message = "Endtime is earlier than starttime.";
                header("Content-Type:application/json; charset=UTF-8");
                echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            } else {
                header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
            }
        } else {
            $dsn = 'mysql:dbname=toDo;host=localhost';
            $dbUser='dbAccess';
            $dbPassword="letMeIn";
    
            //Connect to db
            $db = new PDO($dsn,$dbUser,$dbPassword);
    
            $stmt=$db->prepare('SELECT `password` FROM `users` WHERE `name`=:user');
    
            //Sanitize input
            $user = filter_var($_POST["user"],FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_var($_POST["password"],FILTER_SANITIZE_SPECIAL_CHARS);
    
            if (isset($_POST["testing"])) {
                $db->beginTransaction();
            }
    
            //Check that the username and password are valid otherwise send error
            if ($stmt->execute(["user"=>$user])) {
                if ($row=$stmt->fetch()) {
                    if ($row["password"] == hash("sha1",$password)) {
                        //Create a compilation using data from the user and return as json if successful
                        $compilation = new stdClass();
                        $compilation->activities = new stdClass();
    
                        $stmt=$db->prepare('SELECT `activity`, `starttime`, `endtime`, `status` FROM `activities` WHERE `user`=:user AND `endtime` BETWEEN :from AND :to');
                        if ($stmt->execute(["user"=>$user, "from"=>$from, "to"=>$to])) {
                            $done = 0;
                            $undone = 0;
                            $categories = [];
    
                            while ($row=$stmt->fetch()) {
                                $compilation->activities->{$row["activity"]} = new stdClass();
                                if (isset($categories[$row["activity"]])) {
                                    $categories[$row["activity"]] = $categories[$row["activity"]] + strtotime($row["endtime"]) - strtotime($row["starttime"]);
                                } else {
                                    $categories[$row["activity"]] = strtotime($row["endtime"]) - strtotime($row["starttime"]);
                                }
    
                                if ($row["status"] == 1) {
                                    $done = $done + 1;
                                } else {
                                    $undone = $undone + 1;
                                }
                            }
    
                            foreach (array_keys($categories) as $category) {
                                $compilation->activities->{$category}->duration = $categories[$category];
                            }
    
                            $compilation->done = $done;
                            $compilation->undone = $undone;
    
                        } else {
                            if (isset($_POST["testing"])) {
                                $db->rollBack();
                                $msg = new stdClass();
                                $msg->response = 0;
                                $msg->message = "Could not get activity data for specified user.";
                                header("Content-Type:application/json; charset=UTF-8");
                                echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
                            } else {
                                header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                            }
                        }
    
                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 1;
                            $msg->message = "Operation was successful.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
                        } else {
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($compilation, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        }
                    } else {
                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 0;
                            $msg->message = "The passwords don't match.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
                        } else {
                            header("HTTP/1.1 403 Forbidden; Content-Type:application/json; charset=UTF-8");
                        }
                    }
                } else {
                    if (isset($_POST["testing"])) {
                        $db->rollBack();
                        $msg = new stdClass();
                        $msg->response = 0;
                        $msg->message = "Could not get password for specified user.";
                        header("Content-Type:application/json; charset=UTF-8");
                        echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
                    } else {
                        header("HTTP/1.1 403 Forbidden; Content-Type:application/json; charset=UTF-8");
                    }
                }
            }
        }

    } else {
        if (isset($_POST["testing"])) {
            $msg = new stdClass();
            $msg->response = 0;
            $msg->message = "Missing username and/or password.";
            header("Content-Type:application/json; charset=UTF-8");
            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        } else {
            header("HTTP/1.1 403 Forbidden; Content-Type:application/json; charset=UTF-8");
        }
    }
?>
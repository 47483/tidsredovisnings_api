<?php
    //Please excuse the messy code, had no time to turn things into functions

    declare (strict_types=1);

    //Check for username and password
    if (isset($_POST["user"]) && isset($_POST["password"])) {
        //Connect to db
        $dsn = 'mysql:dbname=toDo;host=localhost';
        $dbUser='dbAccess';
        $dbPassword="letMeIn";

        $db = new PDO($dsn,$dbUser,$dbPassword);

        $stmt=$db->prepare('SELECT `password` FROM `users` WHERE `name`=:user');

        //Sanitize input
        $user = filter_var($_POST["user"],FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($_POST["password"],FILTER_SANITIZE_SPECIAL_CHARS);

        if (isset($_POST["testing"])) {
            $db->beginTransaction();
        }

        $stmt->execute(["user"=>$user]);

        //Check that the username and password are valid otherwise send error
        if ($row=$stmt->fetch()) {
            if ($row["password"] == hash("sha1",$password)) {
                //Check for mode and relevant variables and execute the correct if-statement
                if (empty($_POST["mode"])) {
                    if (isset($_POST["id"])) {
                        $id = filter_var($_POST["id"],FILTER_SANITIZE_SPECIAL_CHARS);
                        //Return a specific activity as json
                        $stmt=$db->prepare('SELECT `activity`, `comment`, `starttime`, `endtime`, `status` FROM `activities` WHERE `user`=:user AND `id`=:id');
                        $stmt->execute(["user"=>$user, "id"=>$id]);

                        if ($row=$stmt->fetch()) {
                            $activity = new stdClass();
                            $activity->category = $row["activity"];
                            $activity->status = $row["status"];
                            $activity->comment = $row["comment"];
                            $activity->from = $row["starttime"];
                            $activity->to = $row["endtime"];

                            if (isset($_POST["testing"])) {
                                $db->rollBack();
                                $msg = new stdClass();
                                $msg->response = 1;
                                $msg->message = "Operation was successful.";
                                header("Content-Type:application/json; charset=UTF-8");
                                echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                            } else {
                                header("Content-Type:application/json; charset=UTF-8");
                                echo json_encode($activity, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                            }
                        } else {
                            if (isset($_POST["testing"])) {
                                $db->rollBack();
                                $msg = new stdClass();
                                $msg->response = 0;
                                $msg->message = "Could not get specific activity.";
                                header("Content-Type:application/json; charset=UTF-8");
                                echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                            } else {
                                header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                            }
                        }
                    } else {
                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 0;
                            $msg->message = "Missing required field 'id'.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                        }
                    }
                } else if ($_POST["mode"] == "add" && isset($_POST["category"]) && isset($_POST["from"]) && isset($_POST["to"])) {
                    //Add a new activity and send result as json
                    $stmt=$db->prepare('INSERT INTO `activities` (`activity`, `comment`, `user`, `starttime`, `endtime`, `status`) VALUES (:category, :comment, :user, :starttime, :endtime, :stat)');

                    $category = filter_var($_POST["category"],FILTER_SANITIZE_SPECIAL_CHARS);
                    
                    if (isset($_POST["comment"])) {
                        $comment = filter_var($_POST["comment"],FILTER_SANITIZE_SPECIAL_CHARS);
                    } else {
                        $comment = "";
                    }

                    $from = filter_var($_POST["from"],FILTER_SANITIZE_SPECIAL_CHARS);
                    $to = filter_var($_POST["to"],FILTER_SANITIZE_SPECIAL_CHARS);

                    if ($stmt->execute(["category"=>$category, "comment"=>$comment, "user"=>$user, "starttime"=>$from, "endtime"=>$to, "stat"=>0])) {
                        $msg = new stdClass();
                        $msg->info = "Successfully added activity";

                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 1;
                            $msg->message = "Operation was successful.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        }
                    } else {
                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 0;
                            $msg->message = "Could not add new activity.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                        }
                    }

                } else if ($_POST["mode"] == "edit" && isset($_POST["id"]) && isset($_POST["category"]) && isset($_POST["from"]) && isset($_POST["to"]) && isset($_POST["status"])) {
                    //Change a activity and send result as json
                    $stmt=$db->prepare('UPDATE `activities` SET `activity`=:category, `comment`=:comment, `user`=:user, `starttime`=:starttime, `endtime`=:endtime, `status`=:stat WHERE `user`=:user AND `id`=:id');

                    $category = filter_var($_POST["category"],FILTER_SANITIZE_SPECIAL_CHARS);
                    
                    if (isset($_POST["comment"])) {
                        $comment = filter_var($_POST["comment"],FILTER_SANITIZE_SPECIAL_CHARS);
                    } else {
                        $comment = "";
                    }

                    $from = filter_var($_POST["from"],FILTER_SANITIZE_SPECIAL_CHARS);
                    $to = filter_var($_POST["to"],FILTER_SANITIZE_SPECIAL_CHARS);

                    $status = filter_var($_POST["status"],FILTER_SANITIZE_SPECIAL_CHARS);

                    $id = filter_var($_POST["id"],FILTER_SANITIZE_SPECIAL_CHARS);

                    if ($stmt->execute(["category"=>$category, "comment"=>$comment, "user"=>$user, "starttime"=>$from, "endtime"=>$to, "stat"=>$status, "id"=>$id])) {
                        $msg = new stdClass();
                        $msg->info = "Successfully updated activity";

                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 1;
                            $msg->message = "Operation was successful.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        }
                    } else {
                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 0;
                            $msg->message = "Could not change activity.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        } else {
                            header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                        }
                    }

                } else if ($_POST["mode"] == "status" && isset($_POST["id"]) && isset($_POST["status"])) {
                    //Change the status of a activity and send result as json
                    $stmt=$db->prepare('UPDATE `activities` SET `status`=:stat WHERE `user`=:user AND `id`=:id');

                    $status = filter_var($_POST["status"],FILTER_SANITIZE_SPECIAL_CHARS);

                    $id = filter_var($_POST["id"],FILTER_SANITIZE_SPECIAL_CHARS);

                    if ($stmt->execute(["user"=>$user, "stat"=>$status, "id"=>$id])) {
                        $msg = new stdClass();
                        $msg->info = "Successfully changed status of activity";

                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 1;
                            $msg->message = "Operation was successful.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        }
                    } else {
                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 0;
                            $msg->message = "Could not change status of activity.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                        }
                    }

                } else if ($_POST["mode"] == "remove" && isset($_POST["id"])) {
                    //Remove a activity and send result as json
                    $stmt=$db->prepare('DELETE FROM `activities` WHERE `user`=:user AND `id`=:id');

                    $id = filter_var($_POST["id"],FILTER_SANITIZE_SPECIAL_CHARS);

                    if ($stmt->execute(["user"=>$user, "id"=>$id])) {
                        $msg = new stdClass();
                        $msg->info = "Successfully removed activity";

                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 1;
                            $msg->message = "Operation was successful.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        }
                    } else {
                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 0;
                            $msg->message = "Could not remove activity.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                        }
                    }

                } else {
                    if (isset($_POST["testing"])) {
                        $db->rollBack();
                        $msg = new stdClass();
                        $msg->response = 0;
                        $msg->message = "Specified mode is invalid or missing required parameters.";
                        header("Content-Type:application/json; charset=UTF-8");
                        echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                    } else {
                        header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                    }
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
                $msg->message = "Can't get password from specified user.";
                header("Content-Type:application/json; charset=UTF-8");
                echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            } else {
                header("HTTP/1.1 403 Forbidden; Content-Type:application/json; charset=UTF-8");
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
            header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
        }
    }
?>
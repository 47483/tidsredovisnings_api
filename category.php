<?php
    //Please excuse the messy code, had no time to turn things into functions

    declare (strict_types=1);

    //Check for username and password
    if (isset($_POST["user"]) && isset($_POST["password"])) {
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

        $stmt->execute(["user"=>$user]);

        //Check that the username and password are valid otherwise send error
        if ($row=$stmt->fetch()) {
            if ($row["password"] == hash("sha1",$password)) {
                //Check for mode and relevant variables and execute the correct if-statement
                if (empty($_POST["mode"])) {
                    if (isset($_POST["id"])) {
                        //Return a specific category as json
                        $stmt=$db->prepare('SELECT `category` FROM `categories` WHERE `user`=:user AND `id`=:id');

                        $id = filter_var($_POST["id"],FILTER_SANITIZE_SPECIAL_CHARS);

                        $stmt->execute(["user"=>$user, "id"=>$id]);

                        if ($row=$stmt->fetch()) {
                            $category = new stdClass();
                            $category->category = $row["category"];

                            if (isset($_POST["testing"])) {
                                $db->rollBack();
                                $msg = new stdClass();
                                $msg->response = 1;
                                $msg->message = "Operation was successful.";
                                header("Content-Type:application/json; charset=UTF-8");
                                echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                            } else {
                                header("Content-Type:application/json; charset=UTF-8");
                                echo json_encode($category, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                            }
                        } else {
                            if (isset($_POST["testing"])) {
                                $db->rollBack();
                                $msg = new stdClass();
                                $msg->response = 0;
                                $msg->message = "Could not get specific category.";
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
                } else if ($_POST["mode"] == "add" && isset($_POST["category"])) {
                    //Add a new category and send result as json
                    $stmt=$db->prepare('INSERT INTO `categories` (`user`, `category`) VALUES (:user, :category)');

                    $category = filter_var($_POST["category"],FILTER_SANITIZE_SPECIAL_CHARS);

                    if ($stmt->execute(["category"=>$category, "user"=>$user])) {
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
                            $msg->message = "Could not add new category.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                        }
                    }

                } else if ($_POST["mode"] == "edit" && isset($_POST["id"]) && isset($_POST["category"])) {
                    //Edit a category and send result as json
                    $stmt=$db->prepare('UPDATE `categories` SET `category`=:category, `user`=:user WHERE `user`=:user AND `id`=:id');

                    $category = filter_var($_POST["category"],FILTER_SANITIZE_SPECIAL_CHARS);

                    $id = filter_var($_POST["id"],FILTER_SANITIZE_SPECIAL_CHARS);

                    if ($stmt->execute(["category"=>$category, "user"=>$user, "id"=>$id])) {
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
                            $msg->message = "Could not update category.";
                            header("Content-Type:application/json; charset=UTF-8");
                            echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                        } else {
                            header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                        }
                    }

                } else if ($_POST["mode"] == "remove" && isset($_POST["id"])) {
                    //Remove a category and send result as json
                    $stmt=$db->prepare('DELETE FROM `categories` WHERE `user`=:user AND `id`=:id');

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
                            $msg->message = "Could not delete category.";
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
                $msg->message = "Could not get password of specified user.";
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
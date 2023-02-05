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

        //Check that the username and password are valid otherwise send error
        if ($stmt->execute(["user"=>$user])) {
            if ($row=$stmt->fetch()) {
                if ($row["password"] == hash("sha1",$password)) {
                    //Return all categories of the user as json
                    $stmt=$db->prepare('SELECT `id`, `category` FROM `categories` WHERE `user`=:user');
                    $categories = new stdClass();
                    if ($stmt->execute(["user"=>$user])) {
                        while ($row=$stmt->fetch()) {
                            $categories->{$row["id"]} = new stdClass();
                            $categories->{$row["id"]}->category = $row["category"];
                        }
                    } else {
                        if (isset($_POST["testing"])) {
                            $db->rollBack();
                            $msg = new stdClass();
                            $msg->response = 0;
                            $msg->message = "Could not get categories for user.";
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
                        echo json_encode($categories, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
                    $msg->message = "Could not get password from specified user.";
                    header("Content-Type:application/json; charset=UTF-8");
                    echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                } else {
                    header("HTTP/1.1 403 Forbidden; Content-Type:application/json; charset=UTF-8");
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
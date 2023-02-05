<?php
    //Please excuse the messy code, had no time to turn things into functions

    //Check for username and password
    if (isset($_POST["user"]) && isset($_POST["password"])) {

        //Sanitize input
        $user = filter_var($_POST["user"],FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_var($_POST["password"],FILTER_SANITIZE_SPECIAL_CHARS);

        $dsn = 'mysql:dbname=toDo;host=localhost';
        $dbUser='dbAccess';
        $dbPassword="letMeIn";

        //Connect to db
        $db = new PDO($dsn,$dbUser,$dbPassword);

        if (isset($_POST["testing"])) {
            $db->beginTransaction();
        }

        //Switch to the correct mode, login or signup
        if (isset($_POST["checkPassword"])) {

            //Sanitize extra input
            $checkPassword = filter_var($_POST["checkPassword"],FILTER_SANITIZE_SPECIAL_CHARS);
            $signup = new stdClass();

            $stmt=$db->prepare('SELECT `name` FROM `users` WHERE `name`=:user');
            
            //Check that the username and passwords are valid otherwise send error
            if($stmt->execute(["user"=>$user])) {
                if (!$stmt->fetch()) {
                    if ($password == $checkPassword) {
                        //Create a new account if successfull and return result as json
                        $stmt=$db->prepare('INSERT INTO `users` (`name`,`password`) VALUES (:user,:pass)');
                        if($stmt->execute(["user"=>$user, "pass"=>hash("sha1",$password)])) {
                            $signup->valid = true;
                            $signup->message = "Account created successfully.";

                        } else {
                            $signup->valid = false;
                            $signup->message = "The account was not created.";
                        }

                    } else {
                        $signup->valid = false;
                        $signup->message = "The passwords don't match.";
                    }
                } else {
                    $signup->valid = false;
                    $signup->message = "Username is already taken.";
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
                    echo json_encode($signup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                }

            } else {
                if (isset($_POST["testing"])) {
                    $db->rollBack();
                    $msg = new stdClass();
                    $msg->response = 0;
                    $msg->message = "Could not find user with specified username.";
                    header("Content-Type:application/json; charset=UTF-8");
                    echo json_encode($msg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                } else {
                    header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
                }
            }

        } else {
            //Check that the username and password are valid and return result as json
            $stmt=$db->prepare('SELECT `password` FROM `users` WHERE `name`=:user');

            if($stmt->execute(["user"=>$user])) {
                $login = new stdClass();

                if ($row=$stmt->fetch()) {
                    if ($row["password"] == hash("sha1",$password)) {
                        $login->valid = true;
                        $login->message = "All fields valid.";

                    } else {
                        $login->valid = false;
                        $login->message = "Invalid password.";
                    }

                } else {
                    $login->valid = false;
                    $login->message = "Invalid username.";
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
                    echo json_encode($login, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
                    header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
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
            header("HTTP/1.1 400 Bad Request; Content-Type:application/json; charset=UTF-8");
        }
    }
?>
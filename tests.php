<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Tests</title>
    <link rel="stylesheet" href="main.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@600&display=swap');

        * {
            font-family: 'Raleway', sans-serif;
        }

        .failed {
            background-color: hsl(0,100%,50%);
            color: hsl(0,0%,100%);
        }

        .success {
            background-color: hsl(150,100%,50%);
            color: hsl(0,0%,100%);
        }

        .test {
            margin-bottom: 0.25em;
        }
    </style>
    <script>
        function testActivities() {
            let fd = new FormData();
            fd.append("testing",true);

            let TA1 = document.createElement("div");
            TA1.innerHTML = "Get All Activities";
            TA1.classList = "test";
            document.body.appendChild(TA1);
            
            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/activities",fd,1,TA1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/activities",fd,0,TA1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/activities",fd,0,TA1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/activities",fd,0,TA1);

            fd = new FormData();
            fd.append("testing",true);

            let TA2 = document.createElement("div");
            TA2.innerHTML = "Get Individual Activity";
            TA2.classList = "test";
            document.body.appendChild(TA2);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("id",51)
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/activity",fd,1,TA2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("id",51)
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/activity",fd,0,TA2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fetchTest("Missing 'id' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("id",-51)
            fd.append("password","apiTestingPWD");
            fetchTest("Using wrong 'id' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/activity",fd,0,TA2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/activity",fd,0,TA2);

            fd = new FormData();
            fd.append("testing",true);

            let TA3 = document.createElement("div");
            TA3.innerHTML = "Add New Activity";
            TA3.classList = "test";
            document.body.appendChild(TA3);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/activity",fd,1,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00wrong");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using wrong format from/to","http://localhost/javascript/toDo/api/activity",fd,0,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","NotDefault");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using non-existent category","http://localhost/javascript/toDo/api/activity",fd,0,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1990-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using invalid from/to","http://localhost/javascript/toDo/api/activity",fd,0,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("mode","add");
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/activity",fd,0,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/activity",fd,0,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fd.append("mode","add");
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/activity",fd,0,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing 'category' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","Default");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing 'comment' parameter","http://localhost/javascript/toDo/api/activity",fd,1,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing 'from' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fetchTest("Missing 'to' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA3);

            fd = new FormData();
            fd.append("testing",true);

            let TA4 = document.createElement("div");
            TA4.innerHTML = "Edit Activity";
            TA4.classList = "test";
            document.body.appendChild(TA4);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/activity",fd,1,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00wrong");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using wrong format from/to","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","NotDefault");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using non-existent category","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1990-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using invalid from/to parameters","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing 'id' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",-51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using wrong 'id' parameter","http://localhost/javascript/toDo/api/activity",fd,1,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing 'status' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing 'category' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("from","1980-11-10 00:00:00");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing 'comment' parameter","http://localhost/javascript/toDo/api/activity",fd,1,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("to","1985-11-10 00:00:00");
            fetchTest("Missing 'from' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",51);
            fd.append("status",0);
            fd.append("category","Default");
            fd.append("comment","A comment.");
            fd.append("from","1980-11-10 00:00:00");
            fetchTest("Missing 'to' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA4);

            fd = new FormData();
            fd.append("testing",true);

            let TA5 = document.createElement("div");
            TA5.innerHTML = "Edit Activity Status";
            TA5.classList = "test";
            document.body.appendChild(TA5);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","status");
            fd.append("id",51);
            fd.append("status",0);
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/activity",fd,1,TA5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("mode","status");
            fd.append("id",51);
            fd.append("status",0);
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/activity",fd,0,TA5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","status");
            fd.append("status",0);
            fetchTest("Missing 'id' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","status");
            fd.append("id",-51);
            fd.append("status",0);
            fetchTest("Using wrong 'id' parameter","http://localhost/javascript/toDo/api/activity",fd,1,TA5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","status");
            fd.append("id",51);
            fetchTest("Missing 'status' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fd.append("mode","status");
            fd.append("id",51);
            fd.append("status",0)
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/activity",fd,0,TA5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fd.append("mode","status");
            fd.append("id",51);
            fd.append("status",0)
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/activity",fd,0,TA5);

            fd = new FormData();
            fd.append("testing",true);

            let TA6 = document.createElement("div");
            TA6.innerHTML = "Remove Activity";
            TA6.classList = "test";
            document.body.appendChild(TA6);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","remove");
            fd.append("id",51);
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/activity",fd,1,TA6);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("mode","remove");
            fd.append("id",51);
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/activity",fd,0,TA6);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fd.append("mode","remove");
            fd.append("id",51);
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/activity",fd,0,TA6);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fd.append("mode","remove");
            fd.append("id",51);
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/activity",fd,0,TA6);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","remove");
            fetchTest("Missing 'id' parameter","http://localhost/javascript/toDo/api/activity",fd,0,TA6);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","remove");
            fd.append("id",-51);
            fetchTest("Using wrong 'id' parameter","http://localhost/javascript/toDo/api/activity",fd,1,TA6);

        }

        function testCategories() {
            let fd = new FormData();
            fd.append("testing",true);

            let TC1 = document.createElement("div");
            TC1.innerHTML = "Get All Categories";
            TC1.classList = "test";
            document.body.appendChild(TC1);
            
            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/categories",fd,1,TC1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/categories",fd,0,TC1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/categories",fd,0,TC1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/categories",fd,0,TC1);

            fd = new FormData();
            fd.append("testing",true);

            let TC2 = document.createElement("div");
            TC2.innerHTML = "Get Individual Categories";
            TC2.classList = "test";
            document.body.appendChild(TC2);
            
            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("id",36);
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/category",fd,1,TC2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("id",36);
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/category",fd,0,TC2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fetchTest("Missing 'id' parameter","http://localhost/javascript/toDo/api/category",fd,0,TC2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("id",-36);
            fetchTest("Using wrong 'id' parameter","http://localhost/javascript/toDo/api/category",fd,0,TC2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fd.append("id",36);
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/category",fd,0,TC2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fd.append("id",36);
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/category",fd,0,TC2);

            fd = new FormData();
            fd.append("testing",true);

            let TC3 = document.createElement("div");
            TC3.innerHTML = "Add New Category";
            TC3.classList = "test";
            document.body.appendChild(TC3);
            
            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","A Category");
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/category",fd,1,TC3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("mode","add");
            fd.append("category","A Category");
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/category",fd,0,TC3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fd.append("category","A Category");
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/category",fd,0,TC3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fd.append("mode","add");
            fd.append("category","A Category");
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/category",fd,0,TC3);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","add");
            fetchTest("Missing 'category' parameter","http://localhost/javascript/toDo/api/category",fd,0,TC3);

            fd = new FormData();
            fd.append("testing",true);

            let TC4 = document.createElement("div");
            TC4.innerHTML = "Edit Category";
            TC4.classList = "test";
            document.body.appendChild(TC4);
            
            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",36);
            fd.append("category","A Category");
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/category",fd,1,TC4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("mode","edit");
            fd.append("id",36);
            fd.append("category","A Category");
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/category",fd,0,TC4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",36);
            fd.append("category","A Category");
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/category",fd,0,TC4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fd.append("mode","edit");
            fd.append("id",36);
            fd.append("category","A Category");
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/category",fd,0,TC4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("id",36);
            fetchTest("Missing 'category' parameter","http://localhost/javascript/toDo/api/category",fd,0,TC4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("category","A Category");
            fetchTest("Missing 'id' parameter","http://localhost/javascript/toDo/api/category",fd,0,TC4);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","edit");
            fd.append("category","A Category");
            fd.append("id",-36);
            fetchTest("Using wrong 'id' parameter","http://localhost/javascript/toDo/api/category",fd,1,TC4);

            fd = new FormData();
            fd.append("testing",true);

            let TC5 = document.createElement("div");
            TC5.innerHTML = "Remove Category";
            TC5.classList = "test";
            document.body.appendChild(TC5);
            
            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","remove");
            fd.append("id",36);
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/category",fd,1,TC5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("mode","remove");
            fd.append("id",36);
            fetchTest("Missing input parameters","http://localhost/javascript/toDo/api/category",fd,0,TC5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fd.append("mode","remove");
            fd.append("id",36);
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/category",fd,0,TC5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fd.append("mode","remove");
            fd.append("id",36);
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/category",fd,0,TC5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","remove");
            fetchTest("Missing 'id' parameter","http://localhost/javascript/toDo/api/category",fd,0,TC5);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("mode","remove");
            fd.append("id",-36);
            fetchTest("Using wrong 'id' parameter","http://localhost/javascript/toDo/api/category",fd,1,TC5);
        }

        function testCompilation() {
            let fd = new FormData();
            fd.append("testing",true);

            let TCO1 = document.createElement("div");
            TCO1.innerHTML = "Get Compilation";
            TCO1.classList = "test";
            document.body.appendChild(TCO1);
            
            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("from","1985-10-08 00:00:00");
            fd.append("to","1985-10-09 00:00:00");
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/compilation",fd,1,TCO1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("from","1985-10-08 00:00:00wrong");
            fd.append("to","1985-10-09 00:00:00");
            fetchTest("Using wrong format from","http://localhost/javascript/toDo/api/compilation",fd,0,TCO1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("from","1985-10-08 00:00:00");
            fd.append("to","1985-10-09 00:00:00wrong");
            fetchTest("Using wrong format to","http://localhost/javascript/toDo/api/compilation",fd,0,TCO1);

            fd = new FormData();
            fd.append("testing",true);


            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("from","1990-10-08 00:00:00");
            fd.append("to","1985-10-09 00:00:00");
            fetchTest("Using invalid from/to parameters","http://localhost/javascript/toDo/api/compilation",fd,0,TCO1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("from","1985-10-08 00:00:00");
            fd.append("to","1985-10-09 00:00:00");
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/compilation",fd,0,TCO1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fd.append("from","1985-10-08 00:00:00");
            fd.append("to","1985-10-09 00:00:00");
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/compilation",fd,0,TCO1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fd.append("from","1985-10-08 00:00:00");
            fd.append("to","1985-10-09 00:00:00");
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/compilation",fd,0,TCO1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("to","1985-10-09 00:00:00");
            fetchTest("Missing 'from' parameter","http://localhost/javascript/toDo/api/compilation",fd,1,TCO1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fd.append("from","1985-10-08 00:00:00");
            fetchTest("Missing 'to' parameter","http://localhost/javascript/toDo/api/compilation",fd,1,TCO1);
        }

        function testUser() {
            let fd = new FormData();
            fd.append("testing",true);

            let TU1 = document.createElement("div");
            TU1.innerHTML = "Get User Account";
            TU1.classList = "test";
            document.body.appendChild(TU1);
            
            fd.append("user","testUser");
            fd.append("password","apiTestingPWD");
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/user",fd,1,TU1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fetchTest("Missing login parameters","http://localhost/javascript/toDo/api/user",fd,0,TU1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserWrong");
            fd.append("password","apiTestingPWD");
            fetchTest("Using wrong username","http://localhost/javascript/toDo/api/user",fd,1,TU1);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUser");
            fd.append("password","apiTestingPWDWrong");
            fetchTest("Using wrong password","http://localhost/javascript/toDo/api/user",fd,1,TU1);

            fd = new FormData();
            fd.append("testing",true);

            let TU2 = document.createElement("div");
            TU2.innerHTML = "Create New Account";
            TU2.classList = "test";
            document.body.appendChild(TU2);
            
            fd.append("user","testUserNew");
            fd.append("password","apiTestingPWD");
            fd.append("checkpassword","apiTestingPWD");
            fetchTest("Using all correct parameters","http://localhost/javascript/toDo/api/user",fd,1,TU2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("password","apiTestingPWD");
            fd.append("checkPassword","apiTestingPWD");
            fetchTest("Missing 'user' parameter","http://localhost/javascript/toDo/api/user",fd,0,TU2);

            fd = new FormData();
            fd.append("testing",true);

            fd.append("user","testUserNew");
            fd.append("checkPassword","apiTestingPWD");
            fetchTest("Missing 'password' parameter","http://localhost/javascript/toDo/api/user",fd,0,TU2);
        }

        function testAll() {
            let TT1 = document.createElement("h1");
            TT1.innerHTML = "Tests For Acitvities";
            TT1.classList = "test";
            document.body.appendChild(TT1);

            testActivities();

            let TT2 = document.createElement("h1");
            TT2.innerHTML = "Tests For Categories";
            TT2.classList = "test";
            document.body.appendChild(TT2);

            testCategories();

            let TT3 = document.createElement("h1");
            TT3.innerHTML = "Tests For Compilation";
            TT3.classList = "test";
            document.body.appendChild(TT3);

            testCompilation();

            let TT4 = document.createElement("h1");
            TT4.innerHTML = "Tests For User";
            TT4.classList = "test";
            document.body.appendChild(TT4);

            testUser();
        }

        function fetchTest(info,dir,formData,expected,parent) {

            fetch(dir,{
                method:"POST",
                body:formData
            })

            .then(response=>{
                if (response.status == 200) {
                    return response.json();
                }
            })

            .then(data=>{
                let result = document.createElement("div");
                let status;
                let expectation;

                if (expected == 1) {
                    expectation = "success";
                } else {
                    expectation = "failed";
                }

                if (data.response == 1) {
                    status = "Success";
                } else {
                    status = "Failed";
                }
                
                if (data.response == expected) {
                    result.innerHTML = "SUCCESS - "+info+": Expected "+expectation+" and got: "+status+" - "+data.message;
                    result.classList = "test success";
                } else {
                    result.innerHTML = "FAILED - "+info+": Expected "+expectation+" and got: "+status+" - "+data.message;
                    result.classList = "test failed";
                }
                parent.appendChild(result);
            })
        }

        function reload() {
            let tests = document.querySelectorAll(".test");
            tests.forEach(test => {
                test.remove();
            })
        }
    </script>
</head>
<body>
    <button onclick="reload();testActivities();">Test Activities</button>
    <button onclick="reload();testCategories();">Test Categories</button>
    <button onclick="reload();testCompilation();">Test Compilation</button>
    <button onclick="reload();testUser();">Test User</button>
    <button onclick="reload();testAll();">Test All</button>
</body>
</html>
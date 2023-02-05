<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>toDo API</title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>
        <h1>API Instructions</h1>
        <p>These are the only valid endpoints of the api, including expected parameters and methods:</p>
        <table>
            <tr>
                <th>Endpoint</th>
                <th>Method</th>
                <th>Description</th>
                <th>Input Params</th>
                <th>Results</th>
            </tr>
            <tr>
                <td>/user/</td>
                <td>POST</td>
                <td>Create a user account.</td>
                <td>[POST-data]<br>- user<br>- password<br>- checkPassword</td>
                <td>Status: 200<br>- valid (bool)<br>- message (string)<br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/user/</td>
                <td>POST</td>
                <td>Log in as a user.</td>
                <td>[POST-data]<br>- user<br>- password</td>
                <td>Status: 200<br>- valid (bool)<br>- message (string)<br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/activities/</td>
                <td>POST</td>
                <td>Get activities for user.</td>
                <td>[POST-data]<br>- user<br>- password</td>
                <td>Status: 200<br>- activities[<br><div class="indent">- id (int)[</div><div class="indentTwo">- activity (string)</div><div class="indentTwo">- status (bool)</div><div class="indentTwo"> - comment (string)</div><div class="indentTwo">- from (datetime)</div><div class="indentTwo">- to (datetime)</div><div class="indent">]</div>]<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/activity/</td>
                <td>POST</td>
                <td>Get selected activity from user.</td>
                <td>[POST-data]<br>- user<br>- password<br>- id</td>
                <td>Status: 200<br>- activity (string)<br>- status (bool)<br>- comment (string)<br>- from (datetime)<br>- to (datetime)<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/activity/</td>
                <td>POST</td>
                <td>Add new activity to user.</td>
                <td>[POST-data]<br>- user<br>- password<br>- mode=add<br>- category<br>- comment<br>- from<br>- to</td>
                <td>Status: 200<br>- message (string)<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/activity/</td>
                <td>POST</td>
                <td>Change activity for user.</td>
                <td>[POST-data]<br>- user<br>- password<br>- mode=edit<br>- id<br>- category<br>- comment<br>- from<br>- to<br>-status</td>
                <td>Status: 200<br>- message (string)<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/activity/</td>
                <td>POST</td>
                <td>Change status of activity for user.</td>
                <td>[POST-data]<br>- user<br>- password<br>- mode=status<br>- id<br>- status</td>
                <td>Status: 200<br>- message (string)<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/activity/</td>
                <td>POST</td>
                <td>Remove activity from user.</td>
                <td>[POST-data]<br>- user<br>- password<br>- mode=remove<br>- id</td>
                <td>Status: 200<br>- message (string)<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/categories/</td>
                <td>POST</td>
                <td>Get categories for user.</td>
                <td>[POST-data]<br>- user<br>- password</td>
                <td>Status: 200<br>- categories[<div class="indent">- id (int)[</div><div class="indentTwo">- category (string)</div><div class="indent">]</div>]<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/category/</td>
                <td>POST</td>
                <td>Get selected category from user.</td>
                <td>[POST-data]<br>- user<br>- password<br>- id</td>
                <td>Status: 200<br>- category (string)<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/category/</td>
                <td>POST</td>
                <td>Add new category to user.</td>
                <td>[POST-data]<br>- user<br>- password<br>- mode=add<br>- category</td>
                <td>Status: 200<br>- message (string)<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/category/</td>
                <td>POST</td>
                <td>Change category for user.</td>
                <td>[POST-data]<br>- user<br>- password<br>- mode=edit<br>- id<br>- category</td>
                <td>Status: 200<br>- message (string)<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/category/</td>
                <td>POST</td>
                <td>Remove category from user.</td>
                <td>[POST-data]<br>- user<br>- password<br>- mode=remove<br>- id</td>
                <td>Status: 200<br>- message (string)<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
            <tr>
                <td>/compilation/</td>
                <td>POST</td>
                <td>Get a compilation of user activities.</td>
                <td>[POST-data]<br>- user<br>- password<br>- from<br>- to</td>
                <td>Status: 200<br>- done (int)<br>- undone (int)<br>- activities[<br><div class="indent">- activity (string)[</div><div class="indentTwo">- duration (seconds)</div><div class="indent">]</div>]<br><br>Status: 400, 403<br>(httpError)</td>
            </tr>
        </table>
    </body>
</html>
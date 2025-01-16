<?php 
session_start();

// Verificăm dacă utilizatorul este autentificat
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Index</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #add8e6; /* Baby blue */
            text-align: center;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        header a {
            text-decoration: none;
            font-size: 18px;
            color: #333;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        header a:hover {
            background-color: #ddd;
        }

        .content {
            margin-top: 50px;
            padding: 20px;
        }

        .content p {
            font-size: 18px;
            font-weight: normal;
            color: #333;
            line-height: 1.6;
            margin: 20px auto;
            width: 80%;
        }

        .winking-image {
            position: absolute;
            right: 10%;
            bottom: 10%;
            width: 300px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .winking-image:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <a href="game.php">Start Game</a> <!-- Link către pagina de joc -->
        <h1>Welcome</h1> <!-- Textul „Welcome” -->
        <a href="logout.php">Log Out</a> <!-- Link pentru delogare -->
    </header>

    <!-- Conținutul principal -->
    <div class="content">
        <p>
            „Bine ați venit la Spânzurătoare! Unde literele sunt la fel de capricioase ca și răspunsurile unui copil când îl întrebi ce vrea să mănânce.  
            Pregătiți-vă pentru un duel al minții și al cuvintelor, dar nu vă faceți griji, nu vă veți spânzura… de nervi, cel puțin.  
            Haideți să înceapă jocul!”
        </p>
    </div>

    <!-- Imaginea care face cu ochiul -->
    <img src="images/winking.png" alt="Winking face" class="winking-image">

</body>
</html>

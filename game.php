<?php 
// Include configurația pentru conexiunea la baza de date
include 'config.php';

// Start sesiunea pentru a reține progresul jocului
session_start();

// Funcție pentru inițializarea unui nou joc
function initializeGame($conn) {
    $sql = "SELECT word, hint FROM words ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['word'] = $row['word'];
        $_SESSION['hint'] = $row['hint'];
        $_SESSION['guessed_letters'] = [];
        $_SESSION['wrong_guesses'] = 0;
    } else {
        echo "Nu există cuvinte în baza de date!";
        exit;
    }
}

// Inițializează jocul dacă nu există o sesiune activă
if (!isset($_SESSION['word'])) {
    initializeGame($conn);
}

// Procesează litera ghicită
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['letter'])) {
    $letter = strtolower($_POST['letter']);

    if (!in_array($letter, $_SESSION['guessed_letters'])) {
        $_SESSION['guessed_letters'][] = $letter;

        if (strpos($_SESSION['word'], $letter) === false) {
            $_SESSION['wrong_guesses']++;
        }
    }
}

// Verifică dacă jocul este câștigat
function isGameWon() {
    foreach (str_split($_SESSION['word']) as $letter) {
        if (!in_array($letter, $_SESSION['guessed_letters'])) {
            return false;
        }
    }
    return true;
}

// Verifică dacă jocul este pierdut
function isGameLost() {
    return $_SESSION['wrong_guesses'] >= 6;
}

// Resetează jocul dacă utilizatorul cere
if (isset($_POST['reset'])) {
    session_unset();
    initializeGame($conn);
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Joc Spânzurătoarea</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #add8e6; /* Baby blue */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            height: 80%;
        }

        .left-section, .right-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .left-section img {
            max-width: 100%;
            max-height: 80%;
        }

        .center-header {
            position: absolute;
            top: 10px;
            width: 100%;
            text-align: center;
            color: #333;
        }

        .center-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .center-header p {
            margin: 5px 0 0;
            font-size: 16px;
            font-style: italic;
        }

        .game-container {
            text-align: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .word {
            font-size: 24px;
            letter-spacing: 10px;
            margin: 20px 0;
        }

        .hint {
            font-style: italic;
            color: #555;
        }

        .keyboard {
            margin-top: 20px;
        }

        .keyboard-row {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .keyboard button {
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #333;
            background-color: #fff;
            cursor: pointer;
            border-radius: 5px;
        }

        .keyboard button:disabled {
            background-color: #aaa;
            cursor: not-allowed;
        }

        .wrong-guesses {
            color: red;
            font-weight: bold;
            margin: 10px 0;
        }

        .reset-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="center-header">
        <h1>Mult succes!</h1>
        <p>Nu lăsa omulețul să apară :P</p>
    </div>

    <div class="container">
        <!-- Secțiunea stângă: Spânzurătoarea -->
        <div class="left-section">
            <img src="images/gallows_step<?= $_SESSION['wrong_guesses']; ?>.png" alt="Spânzurătoare">
        </div>

        <!-- Secțiunea dreaptă: Jocul -->
        <div class="right-section">
            <div class="game-container">
                <?php if (isGameWon()): ?>
                    <h2>Felicitări! Ai ghicit cuvântul: <?= strtoupper($_SESSION['word']); ?> 🎉</h2>
                    <form method="post">
                        <button type="submit" name="reset" class="reset-button">Joacă din nou</button>
                    </form>
                <?php elseif (isGameLost()): ?>
                    <h2>Ai pierdut! Cuvântul era: <?= strtoupper($_SESSION['word']); ?> 😢</h2>
                    <form method="post">
                        <button type="submit" name="reset" class="reset-button">Joacă din nou</button>
                    </form>
                <?php else: ?>
                    <div class="word">
                        <?php
                        foreach (str_split($_SESSION['word']) as $letter) {
                            echo in_array($letter, $_SESSION['guessed_letters']) ? strtoupper($letter) : "_";
                            echo " ";
                        }
                        ?>
                    </div>
                    <div class="hint">Hint: <?= $_SESSION['hint']; ?></div>
                    <div class="wrong-guesses">Greșeli: <?= $_SESSION['wrong_guesses']; ?>/6</div>

                    <form method="post">
                        <div class="keyboard">
                            <!-- Rândul 1 -->
                            <div class="keyboard-row">
                                <?php foreach (['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'] as $letter): ?>
                                    <button type="submit" name="letter" value="<?= strtolower($letter); ?>" 
                                        <?= in_array(strtolower($letter), $_SESSION['guessed_letters']) ? 'disabled' : ''; ?>>
                                        <?= $letter; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <!-- Rândul 2 -->
                            <div class="keyboard-row">
                                <?php foreach (['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'] as $letter): ?>
                                    <button type="submit" name="letter" value="<?= strtolower($letter); ?>" 
                                        <?= in_array(strtolower($letter), $_SESSION['guessed_letters']) ? 'disabled' : ''; ?>>
                                        <?= $letter; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <!-- Rândul 3 -->
                            <div class="keyboard-row">
                                <?php foreach (['Z', 'X', 'C', 'V', 'B', 'N', 'M'] as $letter): ?>
                                    <button type="submit" name="letter" value="<?= strtolower($letter); ?>" 
                                        <?= in_array(strtolower($letter), $_SESSION['guessed_letters']) ? 'disabled' : ''; ?>>
                                        <?= $letter; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=weatherapp;charset=utf8', 'root', '');
} catch(Exception $e) {
    die('Erreur : '.$e->getMessage());
}

$res = $bdd->query("SELECT * FROM météo");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weatherapp</title>
</head>
<body>
    <div class="banner">
        <h1>Weather-app</h1>
    </div>

    <form action="" method="post" class="météo">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Ville</th>
                    <th>Température maximale</th>
                    <th>Température minimale</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $res->fetch()) : ?>
                <tr>
                    <td><input type="checkbox" name="del[]" value="<?php echo $row['ville']; ?>"></td>
                    <td><?php echo $row['ville']; ?></td>
                    <td><?php echo $row['haut']; ?></td>
                    <td><?php echo $row['bas']; ?></td>
                </tr>
            <?php endwhile; 
            $res->closeCursor();
            ?>
            </tbody>
        </table>

        <?php
        if(isset($_POST['del'])) {
            foreach($_POST['del'] as $value) {
                $deleteRes = $bdd->prepare("DELETE FROM météo WHERE ville = :ville");
                $deleteRes->execute([
                    'ville' => $value,
                ]);
            }
        }
        ?>

        <input type="submit" value="Supprimer une ville" class="btn1">
    </form>
    
    <form action="" method="post" class="add">
        <label for="ville">Entrez une ville :</label>
        <input type="text" name="ville" placeholder="Ville"> <br>
        <label for="haut">Entrez une température maximale :</label>
        <input type="text" name="haut" placeholder="Température max"> <br>
        <label for="bas">Entrez une température minimale :</label>
        <input type="text" name="bas" placeholder="Température min"> <br>
        <input type="submit" value="Ajout d'une ville" class="btn2">
    </form>

    <?php
    if(isset($_POST['ville']) && isset($_POST['haut']) && isset($_POST['bas'])) {
        $ville = $_POST['ville'];
        $haut = $_POST['haut'];
        $bas = $_POST['bas'];

        $res2 = $bdd->prepare("INSERT INTO météo(ville, haut, bas) VALUES (:ville, :haut, :bas)");
        $res2->execute([
            'ville' => $ville,
            'haut' => $haut,
            'bas' => $bas,
        ]) or die();

        header("Location: /becode/PHP-pdo/index.php");
        $res2->closeCursor();
    }
    ?>
</body>
</html>

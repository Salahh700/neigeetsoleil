<?php
session_start();

require_once '../../config/Database.php';
require_once '../../models/Gite.php';

$database = new bdd();
$db = $database->getConn();
$unGite = new Gite($db);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'proprietaire') {
    $_SESSION['error'] = "Accès refusé ❌";
    header('location: login.php');
    exit();
}

$res = $unGite->selectGitesByUser($_SESSION['idUser']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neige & Soleil - Mes Logements</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        
        .navbar {
            background: #2c3e50;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar ul { display: flex; gap: 20px; list-style: none; }
        .navbar a { color: white; text-decoration: none; }
        .navbar a:hover { color: #3498db; }
        
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; }
        
        .temporaly-message { margin: 20px auto; max-width: 1200px; }
        .success { background: #2ecc71; color: white; padding: 15px; border-radius: 5px; }
        .error { background: #e74c3c; color: white; padding: 15px; border-radius: 5px; }
        
        h3 { margin-bottom: 20px; color: #2c3e50; }
        
        /* BARRE DE RECHERCHE */
        .search-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .search-section h4 { margin-bottom: 15px; color: #2c3e50; }
        .search-filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        .search-filters input, .search-filters select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }
        .search-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .search-btn:hover { background: #2980b9; }
        
        /* BOUTONS DE TRI */
        .sort-section {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .sort-section h4 { margin-bottom: 10px; color: #2c3e50; }
        .sort-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .sort-btn {
            background: #95a5a6;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .sort-btn:hover { background: #7f8c8d; }
        
        /* TABLEAU */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #34495e;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }
        tr:hover { background: #f8f9fa; }
        
        .status-disponible { color: #2ecc71; font-weight: bold; }
        .status-indisponible { color: #e74c3c; font-weight: bold; }
        
        .actions {
            display: flex;
            gap: 8px;
        }
        .btn-edit {
            background: #f39c12;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-edit:hover { background: #e67e22; }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-delete:hover { background: #c0392b; }
        
        .add-btn {
            background: #2ecc71;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .add-btn:hover { background: #27ae60; }
        
        .etat-bien { color: #f39c12; }
        .etat-tb { color: #3498db; }
        .etat-excellent { color: #2ecc71; }
    </style>
</head>
<body>
    <div class='navbar'>
        <img class="logo" src="../images/logo.png" alt="" style="height: 40px;">
        <ul>
            <li><a href="homeProprietaire.php">🏠 Accueil</a></li>
            <li><a href="mesGites.php">🏘️ Mes logements</a></li>
            <li><a href="profilProprietaire.php">👤 Profil</a></li>
            <li><a href="../../controllers/auth/LogoutController.php">🚪 Déconnexion</a></li>
        </ul>
    </div>

    <div class="container">
        <div class="temporaly-message">
            <?php if (isset($_SESSION['success'])): ?>
                <p class="success">✅ <?= $_SESSION['success'] ?></p>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <p class="error">❌ <?= $_SESSION['error'] ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        </div>

        <h3>Mes logements - <?= $_SESSION['username'] ?></h3>

        <!-- BARRE DE RECHERCHE -->
        <div class="search-section">
            <h4>🔍 Rechercher un logement</h4>
            <form method="GET" action="">
                <div class="search-filters">
                    <input type="text" name="search_ville" placeholder="🏙️ Ville" value="<?= $_GET['search_ville'] ?? '' ?>">
                    <input type="text" name="search_adresse" placeholder="📍 Adresse" value="<?= $_GET['search_adresse'] ?? '' ?>">
                    <input type="number" name="search_prix_min" placeholder="💰 Prix min" value="<?= $_GET['search_prix_min'] ?? '' ?>">
                    <input type="number" name="search_prix_max" placeholder="💰 Prix max" value="<?= $_GET['search_prix_max'] ?? '' ?>">
                    <select name="search_dispo">
                        <option value="">Disponibilité</option>
                        <option value="1" <?= isset($_GET['search_dispo']) && $_GET['search_dispo'] == '1' ? 'selected' : '' ?>>✅ Disponible</option>
                        <option value="0" <?= isset($_GET['search_dispo']) && $_GET['search_dispo'] == '0' ? 'selected' : '' ?>>❌ Indisponible</option>
                    </select>
                    <select name="search_etat">
                        <option value="">État</option>
                        <option value="Bien" <?= isset($_GET['search_etat']) && $_GET['search_etat'] == 'Bien' ? 'selected' : '' ?>>Bien</option>
                        <option value="TB" <?= isset($_GET['search_etat']) && $_GET['search_etat'] == 'TB' ? 'selected' : '' ?>>Très Bien</option>
                        <option value="Excellent" <?= isset($_GET['search_etat']) && $_GET['search_etat'] == 'Excellent' ? 'selected' : '' ?>>Excellent</option>
                    </select>
                </div>
                <button type="submit" class="search-btn">🔍 Rechercher</button>
                <a href="mesGites.php" class="search-btn" style="background: #95a5a6; display: inline-block; text-decoration: none; margin-left: 10px;">🔄 Réinitialiser</a>
            </form>
        </div>

        <!-- BOUTONS DE TRI -->
        <div class="sort-section">
            <h4>📊 Trier par</h4>
            <div class="sort-buttons">
                <a href="?sort=prix_asc" class="sort-btn">💰 Prix ↑</a>
                <a href="?sort=prix_desc" class="sort-btn">💰 Prix ↓</a>
                <a href="?sort=ville_asc" class="sort-btn">🏙️ Ville A→Z</a>
                <a href="?sort=ville_desc" class="sort-btn">🏙️ Ville Z→A</a>
                <a href="?sort=capacite_asc" class="sort-btn">👥 Capacité ↑</a>
                <a href="?sort=capacite_desc" class="sort-btn">👥 Capacité ↓</a>
            </div>
        </div>

        <!-- TABLEAU DES GÎTES -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Adresse</th>
                        <th>Capacité</th>
                        <th>Prix/nuit</th>
                        <th>État</th>
                        <th>Disponibilité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($res)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 30px;">
                                Aucun logement pour le moment
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($res as $gite): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($gite['nomGite']) ?></strong></td>
                                <td><?= htmlspecialchars($gite['villeGite']) ?></td>
                                <td><?= htmlspecialchars($gite['adresseGite']) ?></td>
                                <td><?= $gite['capaciteGite'] ?> pers.</td>
                                <td><?= $gite['prixNuitGite'] ?> €</td>
                                <td>
                                    <?php 
                                    // Pour l'instant affiche "Bien" par défaut (tu ajouteras le champ après)
                                    $etat = $gite['etatGite'] ?? 'Bien';
                                    $class = $etat == 'Excellent' ? 'etat-excellent' : ($etat == 'TB' ? 'etat-tb' : 'etat-bien');
                                    ?>
                                    <span class="<?= $class ?>"><?= $etat ?></span>
                                </td>
                                <td>
                                    <?php if ($gite['disponibiliteGite'] == 1): ?>
                                        <span class="status-disponible">✅ Disponible</span>
                                    <?php else: ?>
                                        <span class="status-indisponible">❌ Indisponible</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <form method="POST" action="updateGite.php" style="display: inline;">
                                            <input type="hidden" name="idGite" value="<?= $gite['idGite'] ?>">
                                            <button type="submit" class="btn-edit">📝 Modifier</button>
                                        </form>
                                        <form method="POST" action="../../controllers/proprietaire/deleteGiteController.php" style="display: inline;">
                                            <input type="hidden" name="id" value="<?= $gite['idGite'] ?>">
                                            <button type="submit" class="btn-delete" onclick="return confirm('❌ Confirmer la suppression ?')">🗑️ Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <a href="addGite.php" class="add-btn">➕ Ajouter un nouveau logement</a>
    </div>
</body>
</html>
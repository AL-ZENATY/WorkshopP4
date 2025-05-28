<h1>Factuur</h1>

<?php
$regels = [
    ['aantal' => 0, 'omschrijving' => 'Rij Kosten', 'prijs' => 12.50],
    ['aantal' => 0, 'omschrijving' => 'Materiaal kosten', 'prijs' => 25.00],
    ['aantal' => 0, 'omschrijving' => 'Uurloon', 'prijs' => 7.99],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aantal'])) {
        foreach ($_POST['aantal'] as $i => $nieuwAantal) {
            if (isset($regels[$i]) && is_numeric($nieuwAantal)) {
                $regels[$i]['aantal'] = intval($nieuwAantal);
            }
        }
    }
}

$totaal = 0;
?>
<form method="post">
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Aantal</th>
        <th>Omschrijving</th>
        <th>Prijs per stuk</th>
        <th>Bedrag</th>
    </tr>
    <?php foreach ($regels as $i => $regel): 
        $bedrag = $regel['aantal'] * $regel['prijs'];
        $totaal += $bedrag;
    ?>
    <tr>
        <td>
            <input type="number" min="0" name="aantal[<?= $i ?>]" value="<?= htmlspecialchars($regel['aantal']) ?>" />
        </td>
        <td><?= htmlspecialchars($regel['omschrijving']) ?></td>
        <td>
            € <?= number_format($regel['prijs'], 2, ',', '.') ?>
        </td>
        <td>€ <?= number_format($bedrag, 2, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
    <?php
        $btw = $totaal * 0.21;
        $excl = $totaal;
        $incl = $totaal + $btw;
    ?>
    <tr>
        <td colspan="3" style="text-align:right;"><strong>Subtotaal (excl. btw)</strong></td>
        <td><strong>€ <?= number_format($excl, 2, ',', '.') ?></strong></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:right;"><strong>BTW (21%)</strong></td>
        <td><strong>€ <?= number_format($btw, 2, ',', '.') ?></strong></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:right;"><strong>Totaal (incl. btw)</strong></td>
        <td><strong>€ <?= number_format($incl, 2, ',', '.') ?></strong></td>
    </tr>
</table>
<input type="submit" value="berekenen" />
</form>

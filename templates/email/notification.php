<?php
/**
 * @var HtmlView $this
 */

use PamutProba\Core\App\View\HtmlView;

$name = $this->data->get("name");
$projectUrl = $this->data->get("projectUrl");
$changes = $this->data->get("changes");
$appName = $this->data->get("appName");
$text = [];

?>
Kedves <?php echo $name ?>,

Ezúton értesítünk, hogy a következő projekt adatai megváltoztak:

<?php echo $projectUrl . "\n" ?>

A megváltozott adatok a következők:
<?php foreach ($changes as $key => $value): ?><?php
match ($key)
{
    "title" => $text[] = "Cím: $value",
    "description" => $text[] = "Leírás: $value",
    "owner" => $text[] = "Kapcsolattartó: {$value["name"]} ({$value["email"]})",
    "status" => $text[] = "Státusz: {$value["name"]}",
    default => $text[] = "Ismeretlen adat: $value"
}
?>
<?php endforeach; ?>
<?php echo implode("\n", $text) ?>

Üdvözlettel:
<?php echo $appName ?> csapat

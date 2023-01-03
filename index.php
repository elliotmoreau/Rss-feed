<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sname = "";
$unmae = "";
$password = "";

$db_name = "";

// Create connection
$con = mysqli_connect($sname, $unmae, $password, $db_name);
// Check connection
if (mysqli_connect_errno($con)) {
  echo "Database connection failed!: " . mysqli_connect_error();
}

$sql = "SELECT * FROM articles ORDER BY id DESC LIMIT 25";
$query = mysqli_query($con, $sql);

header("Content-type: text/xml");
echo '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:georss="http://www.georss.org/georss" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" version="2.0">';
echo "
 <channel>
 <title>La Gazette Tulliste | RSS</title>
 <link>https://journal.elliotmoreau.fr/</link>
 <description>LaGazetteTulliste, le journal de votre ville.</description>
 <language>en-fr</language>";

echo '<atom:link href="https://journal.elliotmoreau.fr/fr/feed/" rel="self" type="application/rss+xml" />';

echo '<image>
<url>https://journal.elliotmoreau.fr/fr/img/logo.png</url>
<title>La Gazette Tulliste | RSS</title>
<link>https://journal.elliotmoreau.fr/</link>
</image>';

if (!ini_get('date.timezone')) {
  date_default_timezone_set('Europe/Paris');
}

while ($row = mysqli_fetch_array($query)) {
  $title = $row["titre"];
  $link = $row["id"];
  $link = "https://journal.elliotmoreau.fr/fr/article.php?id=$link";
  $img = $row["id"];
  $img = "https://journal.elliotmoreau.fr/fr/img/articles/$img.png";
  $description = $row["description"];
  $categorie = $row["categorie"];
  $auteur = $row["auteur"];
  $content = $row["contenu"];
  if (!empty($row["rss_date"])) {
    $date = $row["rss_date"];
  } else {
    $date = $row["date"];
    $date = DateTime::createFromFormat('d/m/Y', $date)->format(DATE_RFC2822);
  }
  echo "<item>
   <title>$title</title>
   <link>$link</link>
   <guid>$link</guid>
   <description>$description</description>
   <pubDate>$date</pubDate>
   <category><![CDATA[  $categorie ]]></category>
   <media:content url='$img' xmlns:media='http://search.yahoo.com/mrss/' type='image/png' medium='image' duration='10'>
   </media:content>
   <content:encoded><![CDATA[ $content ]]></content:encoded>
   <dc:creator>
   <![CDATA[ $auteur ]]> 
   </dc:creator>
   </item>";
}
echo "</channel></rss>";

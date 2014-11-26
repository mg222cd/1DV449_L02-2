1DV449_L02-2
============
<h1>Laboration 02</h1>
<p>
Förutom de förbättringspunkter som finns definierade nedan har PHP-koden strukturerats om endel för att göra den mer
överskådlig och för min del i den här labben lättare att arbeta i. De förändringar jag gjort är bl.a att 
<ul>
<li>Skapat ny klass - Application.php, där funktioner lagts från post.php, get.php, sec.php (dessa filer och klasser är därefter borttagna). Detta för att hålla funktioner och anrop inom samma klass.
</li>
<li>
Bytt namn på filen check.php till functions.php,  och även styrt om action i login-formuläret till functions.php.
</li>
</ul>
</p>
<h2>Del 1 - Säkerhetsproblem</h2>
<h3>Säkerhetsrisk - Skicka in taggar ock kod.</h3>
<ul>
<strong>
Redogör för det säkerhetshål du hittat.
</strong>
<p>
Man kan i alla skicka in HTML-taggar och Javascript utan att applikationen på något vis kontrollerar eller filtrerar detta.
</p>
<strong>
Redogör för hur säkerhetshålet kan utnyttjas.
</strong>
<p>
Illasinnade användare kan utnyttja funktionaliteten att skicka in kod i form av HTML och JavaScript i samband med inloggningen och/eller i meddelanderutorna i chatten.
</p>
<strong>
Vad för skada kan säkerhetsbristen göra?
</strong>
<p>
Genom att skicka in HTML och/eller JavaScript kan man få in kod som förstör applikationen eller för att utföra olika attacker, t.ex XSS (Cross Site Scripting), där man skjuter in länkar/bilder i applikationen som innehåller dolda script för att komma åt användarens sessionscookie. Denna cookie kan sedan användas för att logga in.
</p>
<strong>
Hur du har åtgärdat säkerhetshålet i applikationskoden?
</strong>
<p>
Genom att använda php-funktionen "strip_tags" på allt som skickas in i databasen säkerställs så att allt som skickas in är avskalat från taggar.
</p>

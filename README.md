1DV449_L02-2
============
<h1>Laboration 02</h1>
<p>
Förutom de förbättringspunkter som finns definierade nedan har PHP-koden strukturerats om endel för att göra den mer
överskådlig och för min del i den här labben lättare att arbeta i. De förändringar jag gjort är bl.a att 
<ul>
<li>Skapat ny klass - Application.php, där funktioner lagts från post.php, get.php, sec.php (dessa filer och klasser är därefter borttagna). 
</li>
<li>
Bytt namn på filen check.php till functions.php,  och även styrt om action i login-formuläret till functions.php.
</li>
</ul>
</p>
<h2>Del 1 - Säkerhetsproblem</h2>
<h3>Säkerhetsrisk - Skicka in taggar ock kod.</h3>
<ul>
<li>
Redogör för det säkerhetshål du hittat.
</li>
<li>
Redogör för hur säkerhetshålet kan utnyttjas.
</li>
<p>
Illasinnade användare kan utnyttja funktionaliteten att skicka in kod i form av HTML och Jav i applikationen.
</p>
<li>
Vad för skada kan säkerhetsbristen göra?
</li>
<p>
Genom att skicka in html och/eller JavaScript kan man få in kod för att utföra olika attacker, t.ex XSS, där man skickar in länkar/bilder, och vid klick på dessa har man ett dolt script där man stjäl namnet på användarens sessionscookie. Denna cookie kan sedan användas för att logga in.
</p>
<li>
Hur du har åtgärdat säkerhetshålet i applikationskoden?
</li>
<p>
Genom att använda php-funktionen "strip_tags" på allt som skickas in i databasen säkerställs så att ingen HTML eller JavaScript kan skickas in.
</p>

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
Genom att skicka in HTML och/eller JavaScript kan man få in kod för att förstöra applikationen eller för att utföra olika attacker, t.ex XSS (Cross Site Scripting), där man skjuter in länkar/bilder i applikationen som innehåller dolda script för att komma åt användarens sessionscookie. Denna cookie kan sedan användas för att logga in.
</p>
<strong>
Hur du har åtgärdat säkerhetshålet i applikationskoden?
</strong>
<p>
Genom att använda php-funktionen "strip_tags" på allt som skickas in i databasen säkerställs så att allt som skickas in är avskalat från taggar. Vill här tillägga att jag även försökte använda htmlspecialchars() för att även filtrera utdatat, men detta fick jag ej att fungera.
</p>
<h3>Säkerhetsrisk - SQL-injections</h3>
<ul>
<strong>
Redogör för det säkerhetshål du hittat.
</strong>
<p>
Man kunde skicka in SQL direkt till databasen, via login eller meddelande-fälten.
</p>
<strong>
Redogör för hur säkerhetshålet kan utnyttjas.
</strong>
<p>
Genom att skicka in SQL in i databasen kan man antingen förstöra den eller hämta ut data känslig data från databasen. I det här fallet i form av användarnamn, lösenord och meddelanden.
</p>
<strong>
Vad för skada kan säkerhetsbristen göra?
</strong>
<p>
SQL injections kan användas antingen för att förstöra databasen (och därmed också applikationen) eller för att få ut känsliga uppgifter, tillexempel privata meddelanden och framförallt användaruppgifter och lösenord. Om lösenorden inte är hashade och saltade kan de gå att "knäcka", vilket är extra illa om användaren använt samma lösenorde på i flera applikationer.
</p>
<strong>
Hur du har åtgärdat säkerhetshålet i applikationskoden?
</strong>
<p>
Alla databasfunktioner (och kod som körs många gånger) är flyttade till en ny funktion "callToDatabase". Här används korrekt PDO-syntax och parametrarna körs igenom prepare() execute()-funktionen istället för att läggas in direkt i SQL-frågan.
</p>

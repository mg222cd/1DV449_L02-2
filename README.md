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
<li>
Ändrat från if-satser till switch-case i functions.php, där kontroll görs vilken funktion som ska köras, beroende på vad som ligger i $_GET.
</li>
<li>
Bytt namn på isUser till loginUser.
</li>
<li>
I mess.php har inkluderingen av get.php bytts ut mot Application.php
</li>
</ul>
</p>
<h2>Del 1 - Säkerhetsproblem</h2>
<h3>Säkerhetsrisk - Taggar och kod.</h3>
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
<h3>Säkerhetsrisk - Session hijacking</h3>
<strong>
Redogör för det säkerhetshål du hittat.
</strong>
<p>
Man kunde stjäla sessioner och även återanvända sessioner efter att utloggning skett. Man kunde också ganska lätt skriva egna sessioner, pga ett de namngivits med användarnamn och en enkel standardsträng. 
</p>
<strong>
Redogör för hur säkerhetshålet kan utnyttjas.
</strong>
<p>
De ovan nämnda säkerhetsriskerna gör det tämligen lätt att stjäla sessioner.
</p>
<strong>
Vad för skada kan säkerhetsbristen göra?
</strong>
<p>
Genom att stjäla sessioner kan man logga in på annan användarens konto.
</p>
<strong>
Hur du har åtgärdat säkerhetshålet i applikationskoden?
</strong>
<p>
Sessionerna är nu krypterade, så att det inte lika lätt ska kunna knäckas. Till sessionen har även lagts in HTTPUSERAGENT, så att varje session blir unik och därmed inte går att återanvända av annan dator och webbläsare än där inloggningen gjorts. Funktionalitet har även lagts till för att ta bort sessionen vid utloggning, med "session_destroy" och genom att sätta giltighetstiden till -3600. 
</p>
<h3>Säkerhetsrisk - Oautensierad login</h3>
<strong>
Redogör för det säkerhetshål du hittat.
</strong>
<p>
Man kunde komma in på applikationen utan att göra en giltig login, genom att lägga till "mess.php" i URL:en. Man kunde även skicka in meddelanden i applikationen utan att vara inloggad, via konsolen.
</p>
<strong>
Redogör för hur säkerhetshålet kan utnyttjas.
</strong>
<p>
Obehöriga och ej registrerade användare kommer åt sidor man inte ska komma åt utan att vara inloggad.
</p>
<strong>
Vad för skada kan säkerhetsbristen göra?
</strong>
<p>
Man kan använda och komma åt applikationen utan att vara behörig.
</p>
<strong>
Hur du har åtgärdat säkerhetshålet i applikationskoden?
</strong>
<p>
Dubbelkoll av sessionen sker, via funktionerna checkUser() och loginUser() (här användes tidigare olika sessioner). I funktionen addToDB (i Application-klassen) görs även kontroll så att meddelanden bara kan skickas in av en inloggad användare.
</p>
<h2>Optimering</h2>
<p>
"Boken" som anges nedan, syftar till High Performance Web Sites, Steve Souders.
</p>
<h3>Åtgärd - Färre http-requests</h3>
<strong>
Teori och referens.
</strong>
<p>
Tas upp i kapitel 1 i boken, och sygtar på att man genom att man ska undvika onödiga http-requests genom att använda image-maps, sprites och inline-images. Färre requests kan även uppnås med färre större filer för JS och CSS.
</p>
<strong>
Observation innan åtgård.
</strong>
<p>
Här fanns knappt några bilder att lägga image-maps och sprites på. De få bilder som fanns användes som logotyper eller favicon. Hade kryss-bilden bredvid meddelandena varit implementerad hade man kunnat lägga image-map på den och klockan. Logotyp-bilden ändrades till inline-image, för att undvika request. Tog även bort inlänkade bilden "p.jpg", som inte användes. 
</p>
<strong>
Observation efter åtgärd.
</strong>
<p>
Logon från 230 → 0 ms.<br/>
Oanvänd p.jpg 266 → 0 ms.<br/>
mess.php från 200 → 190 ms.<br/>
</p>
<strong>
Reflektion.
</strong>
<p>
Tidsskillnaden blev tydlig för varje bild, men resultatet knappt märkbart på huvudsidan. Förmodligen större vinst att göra detta i de fall så man har fler bilder på sidan.
</p>
<h3>Inlänkning av filer. JavaScript-filer sist och CSS-filer först i dokumentet.</h3>
<strong>
Teori och referens.
</strong>
<p>
Att placera JavaScript-filer längst ned (istället för längst upp) har att göra med "preogressiv rendering". JavaScript blockerar all rendering så länge scripten läses in, och därför vill man läsa in scripten sist. Detta tas upp i kapitel 6 i boken. Av samma anledning - fast tvärtom (att renderingen är blockad innan all CSS lästs in) vill man lägga in CSS-filerna först i dokumentet, inlänkade med link-taggen (tas upp i kapitel 6 i boken). 
</p>
<strong>
Observation innan åtgård.
</strong>
<p>
All CSS fanns redan inlänkad korrekt med link-taggen i head. Däremot fanns endel JavaScript-filer längst upp i dokumentet. Dessa flyttades till längst ned på sidan.
</p>
<strong>
Observation efter åtgärd.
</strong>
<p>
3 st JavaScript filer flyttades i index.php, från längst upp till längst ned. Ingen tidsskillnad uppstod dock på laddningstiden.
</p>
<strong>
Reflektion.
</strong>
<p>
På själva scripten märks ju ingen tidsskillnad iom. dessa åtgärder. Det som däremot bör märkas på är laddningstiden för hela dokumentet. I det här fallet var dokumentet för litet för att någon märkbar skillnad skulle kunna observeras.
</p>

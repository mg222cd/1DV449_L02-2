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
Man kan i alla fält skicka in HTML-taggar och Javascript utan att applikationen på något vis kontrollerar eller filtrerar detta.
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
<h3>Åtgärd - JavaScript-filer sist och CSS-filer först i dokumentet.</h3>
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
<h3>Åtgärd - JavaScript och CSS externt.</h3>
<strong>
Teori och referens.
</strong>
<p>
I kapitel 8 i boken tas regeln upp om att hålla all JavaScript och CSS extern istället för inline. Detta möjliggör att filerna kan cachas, det minskar storleken på HTML-filen och gör requesten snabbare. Även om JS och CSS laddas lite snabbare om de ligger inline uppvägs detta av att vinsten i att flera sidor kan använda samma filer om de istället ligger externt. Något man nästan alltid vill och behöver göra.
</p>
<strong>
Observation innan åtgård.
</strong>
<p>
I filerna mess.php och index.php har inline-css flyttats till externa filer. I mess.php har inline-javascript flyttats till extern fil som istället länkats in i footern. 
</p>
<strong>
Observation efter åtgärd.
</strong>
<p>
För CSS-filerna: 0 → 100 ms. respektive 0 → 330 ms.<br/>
Javascript-filen: 330 → 335 ms (efter borttagning av tomrader minskning med 2 ms till).<br/>
Huvuddokumentet mess.php 160 → 150 ms.<br/>
Huvuddokumentet index.php 180 → 165 ms.<br/> 
</p>
<strong>
Reflektion.
</strong>
<p>
På tidsskillnaderna ses att huvuddokumenten minskat medan själva filerna ökat. Det man vill komma åt här är ju tidsminskning på själva dokumentet. Här syns det, men det hade synts tydligare om omfattningen på koden som flyttats varit större. Tidsvinsten med att samma fil kan återanvändas istället för att koden ska skrivas om finns inte heller med.
</p>
<h3>Åtgärd - Minifiera JavaScript och CSS</h3>
<strong>
Teori och referens.
</strong>
<p>
Beroende på vilken teknik man använder (JSMin eller Obfuscation) minskar man storleken på sina script och filer genom att ta bort kommentarer, tabbar, blanksteg. Man gör dem även mer svårläsbara för människor. Att göra detta minskar kanske inte storleken lika mycket som att använda Gzip, men är ändå en användbar åtgärd då det minskar storleken på scripten med i snitt 20%. Tas upp i kapitel 10 i boken.
</p>
<strong>
Observation innan åtgård.
</strong>
<p>
CSS flyttades till style.css och Javascripten flyttades till script.min.js.
</p>
<strong>
Observation efter åtgärd.
</strong>
<p>
CSS-filerna: <br/>
dyn.css 322 → 0 ms. samt bootstrap.css 247 → 0 ms. = totalt 559 ms.<br/>
style.css från 0 → 520 ms.</br>
Total minskning 39 ms. (c:a 7%)<br/>
JS-filerna: <br/>
MessageBoard.js 379 → 0 ms, Message.js 395 → 0 ms, jquery.js 655 -> 0 ms, bootstrap.js 457 → 0, script.js 321 → 0
= totalt 2209<br/>
script.min.css från 0 → 892 ms.</br>
Total minskning 1317 ms. (c:a 60%)
</p>
<strong>
Reflektion.
</strong>
<p>
På denna punkt märktes stor skillnad. Förmodligen blev skillnaden, som här var högt över snittet för metoden, här onödigt stor pga att ingen gzip gjorts innan.
</p>


<h3>Åtgärd - Ta bort duplicerade script</h3>
<strong>
Teori och referens.
</strong>
<p>
Tas upp i kapitel 12 i boken. Syftar framförallt på större webbsidor, där det är vanligt att samma script råkar läggas till flera gånger. Detta gör webbsidorna långsammare och skapar i Internet Explorer fler redirects, då alla script (även om dem är samma) utvärderas och läses in på nytt. I Boken föreslås att problemet kan kringgås genom att man använder en script modul för alla filer som läggs till. Där kontrolleras att scriptes inte redan finns, och tas isåfall bort. 
</p>
<strong>
Observation innan åtgård.
</strong>
<p>
I den här applicationen hittades inga dupplicerade scripts, däremot återfanns inlänkade scripts som aldrig användes, vilka togs bort.
</p>
<strong>
Observation efter åtgärd.
</strong>
<p>
bootstrap.js 360 → 0 ms.
longpoll.js 70 → 0 ms.
</p>
<strong>
Reflektion.
</strong>
<p>
Här syns en tydlig skillnad i tidsåtgång för varje script som tas bort. Jag kan tänka mig att det här probelmet är ganska vanligt, att script läggs in på flera sidor pga ren slarvighet och okunskaper i hur det påverkar laddningstiderna.
</p>
<h2>Long polling</h2>
<p>
I samband med att sidan laddas anropas js-funktionen getMessages i MessageBoard, som tar in id-numret på det senaste meddelandet. Varje gång klienten laddar sidan sätts 0 som ett default nummer på senaste meddelandet. Här sätts $_GET['function'] till "getMessages" och även ['id'] sätts. I switch-case-satsen i functions.php, läses "getMessages", som därefter skickar vidare till funktionen getMessages i Application.php. Till denna funktion skickas id från ['id'] med. I funktionen, som inte har någon tidsbegränsning, sker hela tiden ett anrop till databasen där man kollar om det finns något meddelandenummer med id större än det senaste. Om så är fallet hämtas och json-encodas meddelandet och dess id'nr ekas ut. Loopen bryts, och hela processen startar om från början i getMessages i MessageBoard som anropar sig själv och skickar med senaste meddelande-it:t som inparameter.
</p>

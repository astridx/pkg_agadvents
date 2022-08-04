# J4 Advent

0. Zip-Datei `pkg-agadvents-4.x.x.zip` in Joomla 4 installieren. 
1. Beispieldateien installieren: Unter `System | Plugins` das Plugin `Sample Data - Agadvents` aktivieren und danach im `Home Dashboard` die Beispieldatein ` Agadvents Sample Data` installieren.
![1](https://user-images.githubusercontent.com/9974686/161426057-b14d4485-9bf4-4aa7-85f8-23c2dcd6bbf7.PNG)
![2](https://user-images.githubusercontent.com/9974686/161426059-31177d18-5e82-4960-afc1-50f79a2b458b.PNG)

2. Modul anlegen: 
   - Unter `Content | Site Modules` ein Modul vom Typ `Agadvent` auswählen. 
   - Falls der Kalender auf einer `Position` angezeigt werden soll, diese im Starttabulator rechts auswählen. Alternativ in einem Beitrag den Text `{loadmoduleid 'MdoulID'}` eintragen.
   - Für den Adventskalender im Tabulator `Àdvent Parameter` die Kategorie `Weihnachten 2021` auswählen.
3. Bild für den Kalender ist in Optionen zur Kategorie veränderbar. 

4. Die Koordinaten können per Maus gezeichnet werden.
![1](https://user-images.githubusercontent.com/9974686/161427545-2f8f58ec-48f2-46d5-bf3f-a4352cae5295.PNG)

# FAQ

1.  Warning: getimagesize(): http:// wrapper is disabled in the server configuration by allow_url_fopen=0 in /var/www/web317/html/rcnneu/modules/mod_agadvent/tmpl/default.php on line 20

Diese Warnung bedeutet, dass der Webserver ´allow_url_fopen´ deaktiviert hat. Dies ist eine Voraussetzung für agadvents. Bearbeiten Sie die php.ini-Datei Ihrer Website, um die Einstellung zu aktivieren, oder bitten Sie Ihren Hoster, dies zu tun.

[![](https://www.paypalobjects.com/en_US/DK/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KQMKUVAX5SPVS&source=url)

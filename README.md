# Impression

## Voorwoord
Middels dit project hoop ik een beeld te kunnen schetsen van mijn vaardigheden op de gebieden van:
- PHP
- MySQL
- JavaScript
- Object Oriented Programming
- Model / View / Controller principes
- Design Patters
- Werken volgens PHP Coding standaarden
- Alle randzaken rondom HTML / CSS die komen kijken bij het gebruik van een Framework
- Kennis van werken met PHP Frameworks. Een kleine kanttekening daarbij is dat ik reeds nog niet heb mogen werken met frameworks zoals Laravel of Symfony. Uiteraard is de wens er wel hier mee aan de slag te mogen.

PS Werken met Composer en NPM libraries behoort reeds tot de vaardigheden, echter zijn deze betreffende folders niet (of niet goed) terug te vinden in dit project. Binnen het framework van mijn werkgever wordt er gewerkt met Gruntfiles waarbij verworven functionaliteit uit genoemde libraries wordt geïntegreerd in een groot distributiebestand.

## Structuur
Dit project representeert een deel van het MVC principe dat we bij mijn huidige werkgever hanteren. Essentiële lagen, die generiek zijn en de basis vormen van het framework, heb ik ter bescherming van het framework weg gelaten uit dit project. De volgende folders vormen samen de hoogste laag van het framework, namelijk App (de Applicatie laag):
- Components (Hier zijn de services te vinden waarin functionaliteiten worden opgenomen die gegevens ophaalt en verzameld en daarmee kan worden hergebruikt op verschillende plekken in het framework)
- Config (Deze folder bevat een config bestand waarin constanten worden opgenomen die door de applicatie worden gebruikt, waaronder gegevens van de database)
- Controllers (Deze folder bevat de controllers die de afhandeling van de site verzoeken verzorgt. Een endpoint verzoek, URLs, wordt uiteindelijk doorgestuurd naar de correlerende controller en actie die daar bij hoort)
- Models (Deze folder bevat alle classen die de betreffende entiteiten representeren welke thuis horen in dit project en bekend zijn in de database)
- Templates (Deze folder bevat de betreffende templates die worden ingeladen op het moment dat alle afhandelingen zijn gedaan in de controllers en bevat daarmee de informatie die de gebruiker te zijn krijgt op zijn / haar scherm. Binnen deze folder zijn assets (bouwstenen zoals images of css), partials (herbruikbare bouwstenen die delen HTML bevatten) en views terug te vinden)
- Traits (Deze folder bevat een aantal classes die functionaliteit bevatten die kan worden hergebruikt op verschillende plekken / classen binnen het framework, maar niet op zichzelfstaande functionaliteit bevatten.
- Vendors (Deze folder bevat normaal gezien de vanuit composer geïnstalleerde libraries)

## Aanvullende informatie
- Dit project is puur informatief opgezet.
- Het bevat daarmee ook geen endpoint waarbij het project vanuit het oogpunt van de gebruiker bekeken kan worden.
- De code is slechts ter illustratie van mijn vaardigheden en zal daarmee ook vanuit dit oogpunt moeten worden beoordeelt. 

## Take away
Ik hoop hiermee in ieder geval een klein beeld te kunnen schetsen van mijn ervaring, niveau en vaardiheden.
# Contents

## Digital registration
In deze folder vind je verschillende files die toebehoren aan een project t.b.v. digitale registratie van personen die willen deelnemen in vastgoed fondsen. De wens van de klant was een digitale omgeving te hebben waarin (nieuwe) relaties en/of participanten zich kunnen aanmelden voor verschillende fondsen. 

## API / Sync
In deze folder vind je een aantal files die toebehoren aan de functionaliteit die we hebben gebouwd om de 2 systemen synchroon te houden. In de ApiController is functionaliteit terug te vinden voor de ontvangende kant. Hierin wordt de afhandeling van gegevens wijzigingen geïnitieerd. In de CPortalSync vind je functionaliteit die wordt uitgevoerd wanneer wijzigingen vanuit het publieke systeem worden binnen gehaald en moeten worden verwerkt in de interne omgeving. Tijdens deze verwerking vinden eveneens nog verzoeken naar de eerder genoemde API plaats om eventuele wijzigingen en of e-mails in gang te zetten vanuit de publieke omgeving.

## Achtergrond informatie
- Beide folders en files maken onderdeel uit van een groter project dat wij voor een klant hebben ontwikkeld waarin 2 systemen synchroon moeten worden gehouden. Dit ligt met name ten grondslag aan de gesplitste omgevingen waarin deze systemen staan. 1 systeem draait op een interne server van de klant waarbij alleen toegang is verschaft aan werknemers (of uitzonderingen), het anndere systeem draait wel op een publiekelijk te benaderen server. In deze laatste omgeving kunnen relaties (klanten van onze klant) gegevens inzien en wijzigigen van hun deelname bij onze klant. Om de gegevens synchroon te houden in beide systemen hebben wij functionaliteit gebouwd om de gegevens van de ene server over te zetten naar de andere. 
- In beide folders heb ik de files onderverdeeld in een aantal submappen die, op vrij kale manier, het principe van MVC simuleren. In realtime gebruik, zit er meer gelaagdheid in het inhuis gemaakte framework.
- Geen van de folders heeft de mogelijkheid om bekeken te worden in een browser, omdat daar te veel verschillende files voor moeten worden ingeladen. De verschillende files zijn puur ter illustratie.

## Take away
Ik hoop hiermee in ieder geval een klein beeld te kunnen schetsen van mijn ervaring en niveau
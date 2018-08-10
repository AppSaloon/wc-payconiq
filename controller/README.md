# CONTROLLER

Een controller verwerkt alle events in de applicatie. Deze events kunnen door een gebruiker worden geactiveerd. Je kunt hierbij denken aan POST, GET en HTTP request. Een controller vraagt gegevens aan bij een model en verstuurd de data naar een view toe.

Een controller is het bindmiddel tussen views, models en gebruikersinvoer. Daarom bevat een controller het volgende:
- Een controller mag toegang hebben tot de $_GET en $_POST methodes en andere gebruikersinvoer.
- Een controller mag een model instance aanmaken en daar gebruikersinvoer als data aantoevoegen. Let op! het daadwerkelijk opslaan van de data in een database moet in een model gebeuren.
- Er moet voorkomen worden dat controllers SQL-statements bevatten. Dit hoort thuis in een model.
- Er moet voorkomen worden dat er HTML in controllers staat. Alle opmaak hoort in een view.
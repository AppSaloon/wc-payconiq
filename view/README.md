# VIEW

Een view is verantwoordelijk voor de presentatie van de data die het van de model heeft gekregen.

Het ontwikkelen van een applicatie volgens met MVC-model vergemakkelijkt het werken in teamverband. 
De front-end developer wordt slechts lastig gevallen met de User Interface en de backend-developer zal kunnen concentreren op de business logic zonder dat er HTML in zijn code voor komt.

Het is belangrijk om te vermelden dat een model onafhankelijk is van de controller en de view maar dat de controller en de view wel afhankelijk zijn van een model. 
Op deze manier kan een model ontwikkeld worden zonder afhankelijk te zijn van de user interface.

In een view komt alle opmaak van de applicatie terecht. Daarom bevat een view het volgende:

- Een view moet voornamelijk bestaan uit opmaakcode, zoals HTML. Simpele PHP code om een format te wijzigen of gegevens te verlenen zijn ook toegestaan.
- Een view mag direct toegang hebben tot models en controllers. Dit mag echter alleen als het essentieel is voor de presentatie van de applicatie.
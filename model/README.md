# MODEL

Een model is verantwoordelijk voor de business logic. Business Logic is het proces van het ophalen van gegevens. 
Een model is dus verantwoordelijk voor het ophalen van de data uit de database. Daarnaast zijn de CRUD functies onderdeel van een model. 
Belangrijk hierbij is ook het valideren van gegevens.

In een model komt de volgende informatie:

- Een model bevat eigenschappen dat specifieke data vertegenwoordigd
- Een model bevat business logic (bijvoorbeeld validatie code) om aan het ontwerp patroon te voldoen.
- Een model mag code bevatten om data te manipuleren, hierbij kan gedacht worden aan een zoekmethode.

## Voorbeeld Data opbouw van een model die je terugkrijgt

- ID
- post_title
- custom_meta_data
- tax_input
    - taxonomy (string -> post_category)
        - term_id
    - taxonomy (string -> tag)
        - term_id
- log
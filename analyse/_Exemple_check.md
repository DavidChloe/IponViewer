# Exemple to test the application

## Recherche personnalisé

SELECT OBJECT_ID, PARENT_ID, OBJECT_TYPE_ID, NAME, DESCRIPTION 
FROM nc_objects 
WHERE object_id = 9160676156414853441

## Règles
### rule_check_AT($id, $data, $bdd);

ERREUR :
    - (IPON ND/VIA) AT avec ID differentes et le même nom : VIA01000000173657362 
    - (RIP ND/VIA) Absence AT : VIA01000000151629069
    - (IPON ND/VIA) Absence AT dossier: 0148309691

NON TESTE :
    - (IPON ID) : 9126871103813241075

CONFORME : 
    - (IPON ND/VIA) : 0477371517
    - (IPON ID) : 9147883456913126836
    - (IPON ID) : 9160503198614444604
    - (IPON ND/VIA) : VIA01000000119393908
    - (IPON ID) : 9160676156414853441
    - (IPON ND/VIA) dossier + client : 0466479433


### rule_PMO_PC ($via, array $data)

ERREUR

CONFORME :
    - (IPON ND/VIA) : 0477371517
    - (IPON ID) : 9147883456913126836
    - (IPON ND/VIA) : VIA01000000119393908
    - (IPON ID) : 9160676156414853441
    - (IPON ID) : 9126871103813241075

## Script

### script_powerAT ($data, $bdd)

CONFORME :
- (IPON ND/VIA) : 0555060345

CONFORME :
    - (IPON ND/VIA) : 0477371517
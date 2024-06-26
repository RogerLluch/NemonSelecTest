# NemonSelecTest

## Descripció
Test de selecció per a nemon.
El exercici proposat consisteix en el següent:

Es tracta de desenvolupar una petita aplicació en l’entorn de programació que et resulti més
pràctic i que ha de cobrir els següents requeriments:
L’Aplicació és una petita calculadora.
Tindrà un camp per a posar el nom del usuari que realitza l’operació.
Tindrà 3 camps per a introduir els 3 operands (Operand_1, Operand_2, Operand_3 ), que
podrà contenir valors numèrics o alfanumèrics.
Tindrà un botó per a realitzar l’operació.
L’operació que haurà de realitzar és :
Sumar el Operand_1 + Operand_2 + Operand_3, si tots 3 Operands són numérics. Si qualsevol
dels 3 Operands és Alfanuméric, en comptes de sumar els 3 Operands, els concatenarem.
Mostrarà el resultat de l’Operació al usauri.
L’Operand_1 i l’Operand_2 són obligatòris, si l’usuari no els introdueix el sistema haurà de
mostrar un missatge indicant que ha d’informar un valor a ambdós camps.
Cada operació que es realitzi es registrarà en memoria, i es guardarà quin usuari (introduit al
camp nom usuari ) l’ha realitzat.
Si repetim una operació que ja ha estat executada per un altre usuari anteriorment, a banda
de retornar el resultat, retornarem també l’usuari que amb anterioritat havia realitzat
l’operació.

-------------------------------------------------------------------------------------------------

## Configuració

Per a executar aquest test s'ha utilitzat un servidor XAMPP on tindrem una base de dades per a 
guardar les operacions realitzades.
La base de dades emprada s'anomena "calculadora_db" i conté una única taula nomenada "historial".
La taula te l'estructura següent(hi ha que tenir en compte que s'ha d'importar el fitxer "Calculadora_RogerLluch.sql):


![Imatge de l'estructura de la taula](https://raw.githubusercontent.com/RogerLluch/NemonSelecTest/main/EstructuraTaulaHistorial.png)

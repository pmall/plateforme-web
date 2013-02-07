Interface web plateforme analyse de puces à ADN
===============================================

Ce depot contient le code de l'interface web de la plateforme de puces à ADN
que j'ai développé lorsque je travaillais au CRCL.

Cette interface web a été développée avec PHP 5.3 (utilisation des closures)
et utilise une base de données MySQL. Un fichier sql à la racine du depot
contient le schema de cette base de données. Comme les besoins de ce projet
n'étaient pas tres importants, je me suis amusé à développer mon propre
petit framework MVC faisant juste ce qui est nécéssaire pour ce projet,
inspiré de sinatra/slim (repertoire framework).

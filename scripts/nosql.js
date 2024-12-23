db = connect("mongodb://admin:password@localhost:27017/symfony?authSource=admin");

db.jours.insertMany([
    { jour: "lundi", heureOuverture: "09:00", heureFermeture: "18:00" },
    { jour: "mardi", heureOuverture: "09:00", heureFermeture: "18:00" },
    { jour: "mercredi", heureOuverture: "09:00", heureFermeture: "18:00" },
    { jour: "jeudi", heureOuverture: "09:00", heureFermeture: "18:00" },
    { jour: "vendredi", heureOuverture: "09:00", heureFermeture: "20:00" },
    { jour: "samedi", heureOuverture: "10:00", heureFermeture: "20:00" },
    { jour: "dimanche", heureOuverture: "10:00", heureFermeture: "18:00" }
]);

print("Insertion des données terminée !");

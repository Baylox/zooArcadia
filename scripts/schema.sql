-- Table rapport
CREATE TABLE `rapport` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `animal_id` INT DEFAULT NULL,
  `utilisateur_id` INT NOT NULL,
  `titre` VARCHAR(50) NOT NULL,
  `date_rapport` DATE NOT NULL,
  `details` LONGTEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
);


-- Table animal
CREATE TABLE `animal` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `espece_id` INT NOT NULL,
  `habitat_id` INT NOT NULL,
  `prenom` VARCHAR(100) NOT NULL,
  `etat` VARCHAR(50) NOT NULL,
  `sexe` VARCHAR(15) NOT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `dob` DATE NOT NULL,
  `consultations` INT DEFAULT 0,
  PRIMARY KEY (`id`)
);

-- Table espece
CREATE TABLE `espece` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type_espece` VARCHAR(50) NOT NULL,
  `evaluation_extinction` VARCHAR(100) DEFAULT NULL,
  `traits_caracteristiques` LONGTEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Table habitat
CREATE TABLE `habitat` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  `description` LONGTEXT DEFAULT NULL,
  `type_habitat` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Table utilisateur
CREATE TABLE `utilisateur` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(180) NOT NULL,
  `roles` JSON NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nom` VARCHAR(150) NOT NULL,
  `prenom` VARCHAR(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`email`)
);

-- Table service
CREATE TABLE `service` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(150) NOT NULL,
  `description` LONGTEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Table image
CREATE TABLE `image` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `animal_id` INT DEFAULT NULL,
  `habitat_id` INT DEFAULT NULL,
  `service_id` INT DEFAULT NULL,
  `file_name` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Table messenger_messages utilisée par Symfony Messenger pour stocker les messages différés ou en attente,
-- permettant la gestion des tâches asynchrones comme les notifications ou traitements longs. 
CREATE TABLE `messenger_messages` (
  `id` BIGINT AUTO_INCREMENT NOT NULL,
  `body` LONGTEXT NOT NULL,
  `headers` LONGTEXT NOT NULL,
  `queue_name` VARCHAR(190) NOT NULL,
  `created_at` DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
);

ALTER TABLE `animal`
  ADD CONSTRAINT `fk_animal_espece` FOREIGN KEY (`espece_id`) REFERENCES `espece` (`id`),
  ADD CONSTRAINT `fk_animal_habitat` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`);

ALTER TABLE `rapport`
  ADD CONSTRAINT `fk_rapport_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`),
  ADD CONSTRAINT `fk_rapport_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

ALTER TABLE `image`
  ADD CONSTRAINT `fk_image_animal` FOREIGN KEY (`animal_id`) REFERENCES `animal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_image_habitat` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_image_service` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE;
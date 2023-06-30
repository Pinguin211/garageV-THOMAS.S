-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: garageV
-- ------------------------------------------------------
-- Server version	8.0.32-0ubuntu0.20.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `car`
--

DROP TABLE IF EXISTS `car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `car` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `images_names` json NOT NULL,
  `year` int NOT NULL,
  `kilometers` int NOT NULL,
  `fuel_type` int NOT NULL,
  `price` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car`
--

LOCK TABLES `car` WRITE;
/*!40000 ALTER TABLE `car` DISABLE KEYS */;
INSERT INTO `car` VALUES (1,'Lamborghine adventador','{\"1\": \"1.jpg\", \"2\": \"2.jpg\", \"3\": \"3.jpg\", \"4\": \"4.jpg\"}',2010,23566,2,123000),(6,'Fiat 500','{\"1\": \"6482f1b24c85a.jpg\", \"2\": \"6482f1b24c8f1.jpg\", \"3\": \"6482f1b24c93b.jpg\"}',2018,34500,2,12300),(7,'Tesla Model 3','{\"1\": \"6482f280569fe.jpg\", \"2\": \"6482f28056a54.jpg\", \"3\": \"6482f28056a80.jpg\"}',2020,18000,3,23000),(8,'Mini Cooper','{\"1\": \"6482f405699af.jpg\", \"2\": \"6482f40569a1b.jpg\"}',2021,6000,1,24000),(9,'Peugeot 206','{\"1\": \"6482f469955e7.jpg\", \"2\": \"6482f4699563b.jpg\"}',2003,233000,2,9000),(10,'Kart 200cc','{\"1\": \"6482f4c46c43d.jpg\", \"2\": \"6482f4c46c57c.jpg\", \"3\": \"6482f4c46c5ea.jpg\"}',2012,5600,2,2900),(11,'Char de guerre','{\"1\": \"6482f539891fd.jpg\"}',1943,750000,1,59000),(12,'Peugeot 208','{\"1\": \"6482f59555a8e.jpg\", \"2\": \"6482f8871927e.jpg\"}',2019,45000,1,16000),(13,'BMW M3 420Hp','{\"1\": \"6482f66d93887.jpg\", \"2\": \"6482f66d938fc.jpg\", \"3\": \"6482f66d93938.jpg\"}',2016,188000,2,32000),(14,'La voiture sandwich','{\"1\": \"6482f70295f2f.png\", \"2\": \"6482f70295f8d.png\", \"3\": \"6482f70295fd2.png\", \"4\": \"6482f70296005.png\"}',2004,2000,2,299999),(15,'Porshe 911','{\"1\": \"6482f7580f12e.jpg\", \"2\": \"6482f7580f1a1.jpg\"}',1999,18000,2,67000),(16,'Auto-tamponneuse','{\"1\": \"6482f79f5a983.jpg\"}',1988,999999,3,500),(17,'Ferrari 458 GTB','{\"1\": \"6482f85c94d58.jpg\"}',2017,42000,2,258000);
/*!40000 ALTER TABLE `car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car_option`
--

DROP TABLE IF EXISTS `car_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `car_option` (
  `car_id` int NOT NULL,
  `option_id` int NOT NULL,
  PRIMARY KEY (`car_id`,`option_id`),
  KEY `IDX_42EEEC42C3C6F69F` (`car_id`),
  KEY `IDX_42EEEC42A7C41D6F` (`option_id`),
  CONSTRAINT `FK_42EEEC42A7C41D6F` FOREIGN KEY (`option_id`) REFERENCES `option` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_42EEEC42C3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_option`
--

LOCK TABLES `car_option` WRITE;
/*!40000 ALTER TABLE `car_option` DISABLE KEYS */;
INSERT INTO `car_option` VALUES (1,1),(1,2),(1,3),(1,4),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(1,15),(1,16),(1,17),(1,18),(1,19),(1,20),(1,21),(1,22),(1,23),(1,24),(1,25),(1,26),(1,28),(1,36),(6,1),(6,2),(6,4),(6,9),(6,11),(6,13),(6,19),(7,1),(7,3),(7,4),(7,6),(7,7),(7,8),(7,9),(7,10),(7,11),(7,12),(7,13),(7,14),(7,15),(7,16),(7,17),(7,18),(7,20),(7,21),(7,22),(7,23),(7,24),(7,25),(7,26),(7,28),(7,36),(8,2),(8,3),(8,4),(8,13),(8,14),(8,18),(8,19),(8,20),(8,23),(8,25),(8,28),(8,36),(9,19),(11,50),(12,1),(12,4),(12,8),(12,9),(12,11),(12,13),(12,14),(12,18),(12,20),(12,22),(12,26),(12,28),(13,1),(13,3),(13,4),(13,6),(13,7),(13,8),(13,9),(13,11),(13,12),(13,13),(13,14),(13,16),(13,17),(13,18),(13,19),(13,20),(13,21),(13,22),(13,23),(13,25),(13,26),(13,36),(14,23),(14,51),(15,19),(16,36),(17,1),(17,2),(17,3),(17,4),(17,6),(17,7),(17,8),(17,9),(17,10),(17,11),(17,12),(17,13),(17,14),(17,15),(17,16),(17,17),(17,18),(17,19),(17,20),(17,21),(17,22),(17,23),(17,24),(17,25),(17,26),(17,28),(17,36);
/*!40000 ALTER TABLE `car_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `poster_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` int NOT NULL,
  `validated` tinyint(1) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (19,'jean paul LACROIX','Pas pro du tout',1,0,'2023-06-09'),(20,'Pouldu Laure','L\'accueil téléphonique est toujours sympathique, et le service est très réactif <div></div>',5,1,'2023-06-09'),(21,'Michael Lannuzel','Personnel agréable.. Apporte une réponse à la Problematique rencontrée',5,1,'2023-06-09'),(22,'Chantal Le Roux','J\'ai toujours été très bien reçue à l\'accueil',5,1,'2023-06-09'),(23,'Herr.S Romain','Un garage que j\'aurais recommandé jusqu\'à il a peu.',2,0,'2023-06-09'),(24,'antoine le duigou','Problème de leve vitre. Avec un devis trop élevé, j ai refusé l opération',2,0,'2023-06-09'),(25,'nelly mahé','Mr Parrot été super. Intervention rapide et efficace',5,1,'2023-06-09'),(26,'Yannick Josso','Professionnalisme,. 5 voitures gérés par Guillaume et son équipe depuis des années',5,1,'2023-06-09'),(27,'Ludovic Guiffan','Le garage Parrot est très professionnel. En vacances sur la région, une panne aléatoire... ',5,1,'2023-06-09'),(28,'Florence Alain','Depuis des années l\'entretien de mon véhicule je le confie à Guillaume et son équipe.',5,1,'2023-06-09'),(29,'TT DANG','Le patron et les mécaniciens sont sympas et compétents. Ils ne poussent pas à la dépense inutiles',4,1,'2023-06-09'),(30,'Nathalie le gleut','Ça fait environ une bonne quinzaine d\'années que je suis avec eux et pas d\'hésitation <div></div>',5,1,'2023-06-09'),(34,'Lionel','Pouquoi tu get l\'auto ?',4,1,'2023-06-28');
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20230520112615','2023-05-20 11:26:28',741),('DoctrineMigrations\\Version20230526122805','2023-05-26 12:28:20',170),('DoctrineMigrations\\Version20230526123940','2023-05-26 12:39:45',178),('DoctrineMigrations\\Version20230604144004','2023-06-04 14:40:13',36),('DoctrineMigrations\\Version20230614073048','2023-06-14 07:31:04',56);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `message` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
INSERT INTO `message` VALUES (11,'Jean Pierre','0909090909','jp@mail.com','Sujet: Annonce 9 - Peugeot 206\r\n\r\nBonjour je souhaite acheter cette voiture \r\n\r\nCordialement JP','2023-06-28'),(12,'Jul L\'ovni','1234567890','on_mapL@lov.ni','Sujet: Annonce 11 - Char de guerre\r\n\r\nQuel est la consommation pour se type de véhicule ?','2023-06-28');
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `option`
--

DROP TABLE IF EXISTS `option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `option` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `option`
--

LOCK TABLES `option` WRITE;
/*!40000 ALTER TABLE `option` DISABLE KEYS */;
INSERT INTO `option` VALUES (1,'Système de navigation GPS'),(2,'Sièges en cuir'),(3,'Toit ouvrant panoramique'),(4,'Caméra de recul'),(6,'Régulateur de vitesse adaptatif'),(7,'Système d\'assistance au freinage d\'urgence'),(8,'Système de démarrage sans clé'),(9,'Climatisation automatique'),(10,'Système audio haut de gamme'),(11,'Connexion Bluetooth'),(12,'Assistance au stationnement automatique'),(13,'Feux de jour à LED'),(14,'Phares antibrouillard'),(15,'Système de surveillance des angles morts'),(16,'Détection de fatigue du conducteur'),(17,'Assistance au maintien de voie'),(18,'Volant multifonction'),(19,'Jantes en alliage'),(20,'Contrôle de traction et de stabilité'),(21,'Système d\'avertissement de collision'),(22,'Fonctionnalité de connectivité smartphone (Apple CarPlay, Android Auto)'),(23,'Sièges chauffants'),(24,'Système de démarrage à distance'),(25,'Assistance au parking en créneau et en bataille'),(26,'Système de surveillance de la pression des pneus'),(28,'Système de son surround'),(36,'Aide au maintien de la distance de sécurité'),(50,'Canon 120mm'),(51,'Friteuse');
/*!40000 ALTER TABLE `option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `list` json NOT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (4,'Entretien','[\"Vidange d\'huile\", \"Changement de filtres\", \"Contrôle des fluides\", \"Diagnostic électronique\", \"Remplacement de la batterie\", \"Remplacement des courroies\"]','64835ff034328.jpg'),(5,'Roue','[\"Vente de pneus neufs\", \"Montage et équilibrage des pneus\", \"Géométrie et alignement des roues\", \"Réparation de pneus\", \"Gonflage et pression des pneus\", \"Remplacement des valves et des capteurs de pression des pneus\", \"Réglage de la taille des pneus\", \"Réparation et remplacement de freins\"]','64836107ba2a2.jpg'),(6,'Moteur','[\"Diagnostic du moteur\", \"Réparation du système d\'allumage\", \"Réparation du système d\'injection de carburant\", \"Réparation ou remplacement du système de refroidissement\", \"Réparation ou remplacement du système de distribution\", \"Réparation du système de lubrification\"]','6483622fc7fb6.jpg'),(7,'Carrosserie','[\"Réparation des bosses et des rayures\", \"Peinture et retouche\", \"Pose de stickers\", \"Pose de kit\", \"Réparation des phares et des feux arrière\", \"Réparation et remplacement des pare-chocs\", \"Réparation des portières et des panneaux latéraux\"]','6483648f96dbd.jpg'),(9,'Pare brise','[\"Remplacement du pare-brise\", \"Réparation d\'éclats et de fissures\", \"Calibration de la caméra du système d\'assistance au conducteur\", \"Traitement hydrophobe\", \"Réparation ou remplacement des essuie-glaces\"]','64836717d23ee.jpg'),(10,'Amelioration','[\"Reprogrammation moteur\", \"Pose turbo  compresseur\", \"Installation de nitro\", \"Rechargement de nitro\", \"Diagnostic de performance\"]','64836962b6d5d.jpg'),(11,'Collection','[\"Restauration véhicule\", \"Recherche de pièce originale\", \"Fabrication de pièce sur mesure\", \"Estimation de vehicule\", \"Reprise de vehicule\"]','64836ad14fac7.jpg'),(12,'Nettoyage','[\"Nettoyage intérieur complet\", \"Nettoyage extérieur complet\", \"Nettoyage des tapis et des moquettes\", \"Nettoyage des sièges\", \"Nettoyage du compartiment moteur\", \"Élimination des odeurs\", \"Lavage et nettoyage des véhicules utilitaires et des flottes\", \"Polissage des jantes\"]','648365b3d655e.jpg');
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-30  8:02:05
